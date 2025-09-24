<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionalBannerController extends Controller
{
    public function __construct()
    {
        // only users with banner.read can view index & show
        $this->middleware('permission:banner.read')
            ->only(['index', 'show']);

        // only users with banner.create can see the create form & store
        $this->middleware('permission:banner.create')
            ->only(['create', 'store']);

        // only users with banner.update can edit & update
        $this->middleware('permission:banner.update')
            ->only(['edit', 'update']);

        // only users with banner.delete can destroy
        $this->middleware('permission:banner.delete')
            ->only('destroy');
    }
    public function index()
    {
        $banners = PromotionalBanner::all();
        return view('admin.promotional-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.promotional-banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'banner_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'active' => 'required|boolean',
        ]);

        if ($request->hasFile('banner_file')) {
            $path = $request->file('banner_file')->store('banners', 'public');
        }

        PromotionalBanner::insert([
            'banner_file' =>  $path,
            'url' => $request->url,
            'active' => $request->has('active'),
            'created_at' => now()
        ]);
        return redirect()->route('admin.promotional-banners.index')->with('success', 'Banner created.');
    }

    public function edit($banner)
    {
        $banner = PromotionalBanner::findOrFail($banner);
        return view('admin.promotional-banners.edit', compact('banner'));
    }

    public function update(Request $request,  $banner)
    {
        $request->validate([
            'banner_file' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url' => 'nullable|url',
            'active' => 'required|boolean',
        ]);
        $banner = PromotionalBanner::findOrFail($banner);

        $data = [
            'url' => $request->url,
            'active' => $request->has('active'),
            'updated_at' => now()

        ];
        if ($request->hasFile('banner_file')) {
            // Optional: Delete old file
            if ($banner->banner_file && Storage::disk('public')->exists(str_replace('storage/', '', $banner->banner_file))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $banner->banner_file));
            }

            $path = $request->file('banner_file')->store('banners', 'public');
            $data['banner_file'] =  $path;
        }



        $banner->update($data);
        return redirect()->route('admin.promotional-banners.index')->with('success', 'Banner updated.');
    }

    public function show($banner)
    {
        $banner = PromotionalBanner::findOrFail($banner);
        return view('admin.promotional-banners.show', compact('banner'));
    }


    public function destroy($banner)
    {
        $banner = PromotionalBanner::findOrFail($banner);
        // Delete the file if it exists
        if ($banner->banner_file) {
            $filePath = str_replace('storage/', '', $banner->banner_file);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        // Delete the banner record from the database
        $banner->delete();

        return redirect()->route('admin.promotional-banners.index')->with('success', 'Banner deleted.');
    }
}
