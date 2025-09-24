<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductInternal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ProductController extends Controller
{
    //
    public function index()
    {
        $internalProducts = ProductInternal::isActive()->get();
        return view('customer.products.index', compact('internalProducts'));
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
}
