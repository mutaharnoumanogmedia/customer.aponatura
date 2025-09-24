<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductInternal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\ProductInternalRequest;
use Illuminate\Support\Facades\Storage;

class ProductInternalController extends Controller
{
    public function __construct()
    {
        // only users with product.read can view index & show
        $this->middleware('permission:product.read')
            ->only(['index', 'show']);

        // only users with product.create can see the create form & store
        $this->middleware('permission:product.create')
            ->only(['create', 'store']);

        // only users with product.update can edit & update
        $this->middleware('permission:product.update')
            ->only(['edit', 'update']);

        // only users with product.delete can destroy
        $this->middleware('permission:product.delete')
            ->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.product-internal.index');
    }

    public function data(Request $request)
    {
        $query = ProductInternal::query()->orderBy("created_at", "desc")->select([
            'id',
            'sku',
            'product_type',
            'price',
            'status',
            'created_at'
        ]);

        // Apply filters
        if ($request->filled('sku')) {
            $query->where('sku', 'like', '%' . $request->sku . '%');
        }

        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->editColumn('product_type', function ($row) {
                $class = $row->product_type === "physical" ? 'primary' : 'secondary';
                return '<span class="badge bg-' . $class . '">' . ucfirst($row->product_type) . '</span>';
            })
            ->editColumn('status', function ($row) {
                $class = $row->status === 'in_stock' ? 'success' : 'danger';
                return '<span class="badge bg-' . $class . '">' . ucfirst(str_replace('_', ' ', $row->status)) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('admin.product-internal.show', $row->id);
                $editUrl = route('admin.product-internal.edit', $row->id);
                $deleteUrl = route('admin.product-internal.destroy', $row->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');
                $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning mx-1">Edit</a>';
                $deleteBtn = '
                    <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure?\')">
                        ' . $csrf . $method . '
                        <button type="submit" class="btn btn-sm btn-outline-danger mx-1">Remove</button>
                    </form>
                ';
                $viewBtn = '<a href="' . $viewUrl . '" class="btn btn-sm btn-outline-info mx-1">View</a>';
                return $viewBtn . $editBtn . $deleteBtn;
            })
            ->editColumn('price', function ($row) {
                return config('app.currency', 'EUR') . ' ' . number_format($row->price, 2, ',', '.');
            })
            ->rawColumns(['product_type', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product-internal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductInternalRequest $request)
    {
        $data = $request->validated();
        // handle file uploads
        if ($f = $request->file('image_path')) {
            $data['image_path'] = $f->store('products/images', 'public');
        }
        if ($f = $request->file('file_path')) {
            $path = $f->store('products/files', 'public');
            $data['file_path']    = $path;
            $data['download_url'] = Storage::url($path);
        }
        $data['created_by'] = auth()->id();

        ProductInternal::create($data);

        return redirect()
            ->route('admin.product-internal.index')
            ->with('success', 'Product added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $productInternal = ProductInternal::findOrFail($id);
        return view('admin.product-internal.show', compact('productInternal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductInternal $productInternal)
    {
        return view('admin.product-internal.edit', compact('productInternal'));
    }

    public function update(ProductInternalRequest $request, ProductInternal $productInternal)
    {
        $data = $request->validated();
        if ($f = $request->file('image_path')) {
            $data['image_path'] = $f->store('products/images', 'public');
        }
        if ($f = $request->file('file_path')) {
            $path = $f->store('products/files', 'public');
            $data['file_path']    = $path;
            $data['download_url'] = Storage::url($path);
        }
        $data['updated_by'] = auth()->id();

        $productInternal->update($data);

        return redirect()
            ->route('admin.product-internal.index')
            ->with('success', 'Product updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductInternal $productInternal)
    {
        $productInternal->delete();
        return back()->with('success', 'Product deleted.');
    }
}
