<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OrderService
{
    // Add your order-related business logic here

    public function getOrdersFromApi($skip = 0, $take = 100)
    {
        //end point is /api/customer-portal/all-orders/
        $url = env("APP_WARENSYS") . "/api/customer-portal/all-orders/$skip/$take";
        $response = Http::withoutVerifying()->get($url);
        if ($response->successful()) {
            $data = $response->json(); // Decode JSON response to array
            return
                [
                    'success' => true,
                    'orders' => $data['orders'] ?? [],
                    'total' => $data['total'] ?? 0,
                ];
        } else {
            // Handle error response
            return [
                'success' => false,
                'error' => $data['error'] ?? 'API request failed.',
                'code' => $response->status(),
            ];
        }
    }
}
