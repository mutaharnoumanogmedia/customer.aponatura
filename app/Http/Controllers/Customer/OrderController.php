<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Enums\OrderStatus;

class OrderController extends Controller
{
    //
    // Show the customer orders
    public function index(Request $request)
    {
        $data["orders"] = [];
        //guard against unauthorized access

        $customer_email = auth()->guard("customer")->user()->email;
        $response = Http::withoutVerifying()->get(env("APP_WARENSYS") . "/api/customer-portal/customer-orders/$customer_email");

        if ($response->successful()) {
            $response_data = $response->json();
        }

        if (isset($response_data['orders'])) {
            $orders = $response_data['orders'];
            foreach ($orders as &$order) {
                $order['valid_for_review'] = (
                    isset($order['status']) &&
                    in_array($order['status'], OrderStatus::forCategory('completed'))
                    ||
                    isset($order['datum']) &&
                    \Carbon\Carbon::parse($order['datum'])->diffInDays(now()) >= 7
                );
            }
            unset($order); // Unset reference to avoid issues

        } else {
            $orders = [];
        }

        if ($request->has("status")) {
            if ($request->status == "active") {
                $activeCodes = OrderStatus::forCategory('active');  // returns an array of ints

                $orders = array_filter($orders, function ($order) use ($activeCodes) {
                    return in_array($order['status'], $activeCodes);
                });
            }
            if ($request->status == "completed") {
                $completedCodes = OrderStatus::forCategory('completed');  // returns an array of ints

                $orders = array_filter($orders, function ($order) use ($completedCodes) {
                    return in_array($order['status'], $completedCodes);
                });
            }
            if ($request->status == "pending") {
                $pendingCodes = OrderStatus::forCategory('pending');  // returns an array of ints
                $orders = array_filter($orders, function ($order) use ($pendingCodes) {
                    return in_array($order['status'], $pendingCodes);
                });
            }
        }

        return view('customer.orders.index', [
            'orders' => $orders,
            'customer' =>  auth()->guard('customer')->user() ?? null,
        ]);
    }
    // Show the customer order details
    public function show($order_number)
    {
        $response = Http::withoutVerifying()->get(env("APP_WARENSYS") . "/api/customer-portal/order/{$order_number}");
        // dd($response->json());

        if ($response->successful() && isset($response['order'])) {
            $orders = $response['order'];
            return view('customer.orders.show', compact('orders', 'order_number'));
        } else {
            return redirect()->back()->with('error', 'Order not found.');
        }
    }

    public function invoices()
    {
        $response = Http::withoutVerifying()->get(env("APP_WARENSYS") . "/api/customer-portal/customer-invoices/" . auth()->guard("customer")->user()->email);

        if ($response->successful() && isset($response['invoices'])) {

            $invoices = $response['invoices'];

            return view('customer.orders.invoices', compact('invoices'));
        } else {
            return redirect()->back()->with('error', __('Invoices not found'));
        }
    }

    public function invoice(string $invoice_number)
    {
        $customer_email = auth()->guard('customer')->user()->email;
        $url = env('APP_WARENSYS')
            . "/api/customer-portal/customer-invoice/{$customer_email}/invoice/{$invoice_number}";

        $response = Http::withoutVerifying()->get($url);
        // dd($response->json(), $url);

        // If we got a 2xx and the JSON has an "invoice" key…
        if ($response->ok() && isset($response->object()->invoice)) {
            // Grab the invoice as a stdClass
            $invoice = $response->object()->invoice;

            return view('customer.orders.invoice', compact('invoice'));
        }

        return redirect()
            ->back()
            ->with('error', 'Invoice not found.');
    }
}
