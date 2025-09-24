<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function __construct()
    {
        // only users with order.read can view index & show
        $this->middleware('permission:order.read')
            ->only(['index', 'show']);

        // only users with order.create can see the create form & store
        $this->middleware('permission:order.create')
            ->only(['create', 'store']);

        // only users with order.update can edit & update
        $this->middleware('permission:order.update')
            ->only(['edit', 'update']);

        // only users with order.delete can destroy
        $this->middleware('permission:order.delete')
            ->only('destroy');
    }
    //
    public function index(Request $request)
    {
        $skip = $request->input('skip', 0);
        $limit = $request->input('limit', 500);
        $allOrders = []; // Fetch all orders from the database
        $allOrders = (new \App\Services\OrderService())->getOrdersFromApi($skip, $limit);
        return view('admin.orders.index', compact('allOrders'));
    }

    public function show($order_number)
    {
        $response = Http::withoutVerifying()->get(env("APP_WARENSYS") . "/api/customer-portal/order/{$order_number}");
        // dd($response->json(), $order_number);

        if ($response->successful() && isset($response['order'])) {
            $orders = $response['order'];
            return view('admin.orders.show', compact('orders', 'order_number'));
        } else {
            return redirect()->back()->with('error', 'Order not found.');
        }
    }
}
