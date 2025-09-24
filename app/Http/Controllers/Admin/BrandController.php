<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Brand;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function __construct()
    {
        // only users with brand.read can view index & show
        $this->middleware('permission:brand.read')
            ->only(['index', 'show']);

        // only users with brand.create can see the create form & store
        $this->middleware('permission:brand.create')
            ->only(['create', 'store']);

        // only users with brand.update can edit & update
        $this->middleware('permission:brand.update')
            ->only(['edit', 'update']);

        // only users with brand.delete can destroy
        $this->middleware('permission:brand.delete')
            ->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // Fetch all brands from the database
        $brands = Brand::all();
        // Return the view with the brands data
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'slug' => 'required|unique:brands',
            'name' => 'required',
            'domain' => 'required|unique:brands',
            'website_url' => 'required|url',
            'logo_path' => 'nullable|image',
            'favicon_path' => 'nullable|image',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'title' => 'nullable|string',
            'slogan' => 'nullable|string',
            'support_email' => 'nullable|email',
            'is_active' => 'boolean',
            'terms_condition' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'imprint' => 'nullable|string',
            'config' => 'nullable|string',
        ]);

        if ($request->hasFile('logo_path')) {
            $file = $request->file('logo_path');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $validated['logo_path'] = $file->storeAs('brands/' . $request->slug, $filename, 'public');
        }

        if ($request->hasFile('favicon_path')) {
            $file = $request->file('favicon_path');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $validated['favicon_path'] = $file->storeAs('brands/' . $request->slug, $filename, 'public');
        }

        Brand::insert($validated);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {


        $validated = $request->validate([
            'slug' => 'required|unique:brands,slug,' . $brand->id,
            'name' => 'required',
            'domain' => 'required|unique:brands,domain,' . $brand->id,
            'website_url' => 'required|url',
            'logo_path' => 'nullable|image',
            'favicon_path' => 'nullable|image',
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'title' => 'nullable|string',
            'slogan' => 'nullable|string',
            'support_email' => 'nullable|email',
            'is_active' => 'boolean',
            'config' => 'nullable|json',
        ]);

        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        if ($request->hasFile('favicon_path')) {
            $validated['favicon_path'] = $request->file('favicon_path')->store('favicons', 'public');
        }

        $brand->update($validated);
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted.');
    }



    public function removeMedia(Request $request, Brand $brand, $type)
    {
        // only allow logo or favicon
        if (!in_array($type, ['logo', 'favicon'])) {
            return response()->json(['success' => false, 'message' => 'Invalid media type'], 400);
        }

        $field = $type . '_path';  // e.g. logo_path or favicon_path
        if ($brand->$field) {
            Storage::delete($brand->$field);
            $brand->$field = null;
            $brand->save();
        }

        return response()->json(['success' => true, 'message' => ucfirst($type) . ' removed successfully.']);
    }




    public function updateMedia(Request $request, Brand $brand, $type)
    {
        // Only allow logo or favicon
        if (! in_array($type, ['logo', 'favicon'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid media type'
            ], 400);
        }

        // Validate incoming file
        $fieldName = $type; // e.g. 'logo' or 'favicon'
        $request->validate([
            $fieldName => 'required|image|max:5120', // max 5MB
        ]);

        // Determine DB field and disk path
        $dbField    = $type . '_path';      // e.g. 'logo_path'
        $storageDir = "/brands/{$brand->slug}";

        // Delete old file if present
        if ($brand->$dbField) {
            Storage::delete($brand->$dbField);
        }

        // Store new file

        $uploaded = $request->file($type)->storeAs('brands/' . $brand->slug, "{$fieldName}." . $request->file($fieldName)->getClientOriginalExtension(), 'public');


        // Update brand record
        $brand->$dbField = $uploaded;
        $brand->save();

        // Build a public URL (assuming 'public' disk is symlinked)
        $publicUrl = Storage::url($uploaded);

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' updated successfully.',
            'url'     => $publicUrl,
        ]);
    }
}
