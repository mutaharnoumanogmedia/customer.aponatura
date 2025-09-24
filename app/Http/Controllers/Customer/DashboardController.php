<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Admin\ShopifyProduct;
use App\Models\PromotionalBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Enums\OrderStatus;

class DashboardController extends Controller
{
    //
    // Show the customer dashboard
    public function dashboard()
    {
        if (isset($brand)) {
            $products = ShopifyProduct::where('brand', $brand->name)->skip(0)->take(20)->orderBy('created_at', 'desc')->get();
        } else {
            $products = ShopifyProduct::orderBy('created_at', 'desc')
                ->skip(0)
                ->take(20)
                ->get();
        }

        $promotional_banners = PromotionalBanner::where('active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $customer = auth()->guard("customer")->user();
        $customer_id = $customer->id;

        // Cache key per customer
        $cacheKey = "customer_orders_{$customer_id}";

        // Cache the response for 30 minutes
        if (Cache::has($cacheKey)) {
            $response_data = Cache::get($cacheKey);
        } else {
            $response = Http::withoutVerifying()
                ->get(env("APP_WARENSYS") . "/api/customer-portal/customer-orders/{$customer->email}");

            if ($response->successful()) {
                $response_data = $response->json();
            } else {
                $response_data = ['orders' => []]; // Fallback
            }

            Cache::put($cacheKey, $response_data, now()->addMinutes(30));
        }
        $orders = $response_data['orders'] ?? [];


        $activeCodes = OrderStatus::forCategory('active');  // returns an array of ints

        $activeOrders = array_filter($orders, function ($order) use ($activeCodes) {
            return in_array($order['status'], $activeCodes);
        });

        $completedCodes = OrderStatus::forCategory('completed');  // returns an array of ints
        $completedOrders = array_filter($orders, function ($order) use ($completedCodes) {
            return in_array($order['status'], $completedCodes);
        });
        $pendingCodes  = OrderStatus::forCategory('pending');  // returns an array of ints
        $pendingOrders = array_filter($orders, function ($order) use ($pendingCodes) {
            return in_array($order['status'], $pendingCodes);
        });

        return view('customer.dashboard', compact('products', 'promotional_banners', 'orders', 'activeOrders', 'completedOrders', 'pendingOrders'));
    }

    public function profile()
    {
        $countries = \App\Models\Countries::all();
        $customer = auth()->guard("customer")->user();
        return view('customer.profile', compact('countries', 'customer'));
    }
    public function settings()
    {
        return view('customer.settings');
    }

    public function editProfile()
    {
        $user = auth()->guard("customer")->user();
        return view('customer.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
       
    }




    public function getPhoneNumberVerified(){
        $customerWhatsapp = auth()->guard("customer")->user()->phone;

        if ($customerWhatsapp == "" || $customerWhatsapp == null) {
           return response()->json(['found' => false, 'message' => 'Please enter your WhatsApp number'], 200);
        }
        return response()->json(['found' => true, 'phone' => $customerWhatsapp], 200);
    }
}
