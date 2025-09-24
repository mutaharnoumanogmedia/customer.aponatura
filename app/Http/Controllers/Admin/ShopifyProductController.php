<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\product;
use App\Models\Admin\ShopifyProduct;
use App\Services\ShopifyGraphQLService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ShopifyProductController extends Controller
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
    //
    public function index()
    {
        $products = ShopifyProduct::all();

        // Return the view with the products data
        return view('admin.shopify_products.index', compact('products'));
    }
    public function show(ShopifyProduct $product)
    {
        return view('admin.shopify_products.show', compact('product'));
    }



    public function data(Request $request)
    {
        // 1. Start with your base Eloquent query
        $query = ShopifyProduct::select([
            'id',
            'shopify_id',
            'image_url',
            'online_store_url',
            'title',
            'price',
            'brand',
            'currency',
            'show_to_customer',
            'shopify_created_at',
        ]);

        return DataTables::of($query)
            // 2. Global search override (optional—Yajra does this out of the box)
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('shopify_id', 'like', "%{$search}%")
                            ->orWhere('title',        'like', "%{$search}%")
                            ->orWhere('online_store_url', 'like', "%{$search}%")
                            ->orWhere('product',     'like', "%{$search}%");
                    });
                }
            })
            // 2.1. Custom search for specific columns
            // 3. Column‐by‐column filtering (for footer inputs)
            ->filterColumn('price', function ($query, $keyword) {
                // e.g. allow range filtering "10-100"
                if (preg_match('/(\d+)\s*-\s*(\d+)/', $keyword, $m)) {
                    $query->whereBetween('price', [$m[1], $m[2]]);
                } else {
                    $query->where('price', $keyword);
                }
            })
            // 4. Custom ordering for special columns
            // Set default order: latest (highest id) first
            ->order(function ($query) use ($request) {
                if (!$request->has('order')) {
                    $query->orderByDesc('id');
                } else {
                    // If there is an order, use the first one
                    $order = $request->input('order.0');

                    $column = $request->input('columns.' . $order['column'] . '.data');
                    $direction = $order['dir'] === 'asc' ? 'asc' : 'desc';
                    $query->orderBy($column, $direction);
                }
            })

            // 5. Format or add columns
            ->editColumn('show_to_customer', function ($row) {
                return $row->show_to_customer
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-secondary">No</span>';
            })
            ->editColumn('image_url', function ($row) {
                return '<img src="' . $row->image_url . '" alt="' . $row->title . '" class="img-thumbnail" style="width: 100px; height: auto;">';
            })
            ->editColumn('online_store_url', function ($row) {
                return '<a href="' . $row->online_store_url . '" target="_blank" class="btn btn-sm btn-link">View Online</a>';
            })
            ->editColumn('price', function ($row) {
                return ("<small>{$row->currency}</small>") . ' ' . (number_format($row->price, 2, ',', '.'));
            })
            ->addColumn('actions', function ($row) {
                return '<a href="' . route('admin.shopify-products.show', $row->id) .
                    '" class="btn btn-sm btn-primary">View</a>';
            })
            // 6. Tell Yajra which columns contain raw HTML
            ->rawColumns(['show_to_customer', 'actions', 'image_url', 'online_store_url', 'price'])
            // 7. Finalize
            ->make(true);
    }


    public function syncForm()
    {
        // This method can be used to return a view with a form to sync products from Shopify
        $products = product::all();
        return view('admin.shopify_products.sync_form', compact('products'));
    }


    public function sync(Request $request)
    {
        // This method can be used to sync products from Shopify to the database for a specific product
        // You can implement the logic here to fetch products from Shopify API
        // and save them in the ShopifyProduct model for the specified product.

        $shopifyGraphQLService = new ShopifyGraphQLService();
        if ($request->has('product')) {
            $product = $request->input('product');
            $syncResponse = $shopifyGraphQLService->syncProductsToDatabase($product);
        } else {
            $syncResponse = $shopifyGraphQLService->syncProductsToDatabase();
        }


        if ($syncResponse['success']) {
            return response()->json(['message' => $syncResponse['message'], 'data' => $syncResponse['data']]);
        } else {
            return response()->json(['message' => $syncResponse['message']], 500);
        }
    }
}
