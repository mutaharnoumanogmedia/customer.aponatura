<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportChatLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SupportController extends Controller
{
    //


    public function getCustomerOrders(Request $request)
    {
        $data["orders"] = [];
        //guard against unauthorized access

        $customer_email = auth()->guard("customer")->user()->email;
        $skip = $request->query('skip', 0);
        $take = $request->query('take', 10);

        $response = Http::withoutVerifying()->get(
            env("APP_WARENSYS") . "/api/customer-portal/customer-orders/$customer_email",
            [
                'skip' => $skip,
                'take' => $take
            ]
        );

        if ($response->successful()) {
            $response_data = $response->json();
        }

        if (isset($response_data['orders'])) {
            $orders = $response_data['orders'];

            unset($order); // Unset reference to avoid issues

        } else {
            $orders = [];
        }

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'message' => 'Orders retrieved successfully'
        ]);
    }


    public function savePhoneNumber(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:15',
        ]);

        $customer = auth()->guard('customer')->user();
        $customer->phone_number = $request->input('phone_number');
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Phone number saved successfully'
        ]);
    }


    public function storeSupportChatLog(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'sender' => 'required|string|in:user,bot'
        ]);

        $customer = auth()->guard('customer')->user();
        $chatLog = new SupportChatLog();
        $chatLog->customer_email = $customer->email;
        $chatLog->message = $request->input('message');
        $chatLog->sender = $request->input('sender');
        $chatLog->date = date('Y-m-d');
        $chatLog->created_at = now();

        $chatLog->save();

        return response()->json([
            'success' => true,
            'message' => 'Chat log saved successfully'
        ]);
    }
}
