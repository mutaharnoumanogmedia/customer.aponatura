<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Http;

class CustomerVerificationFlowController extends Controller
{
    //
    public function verifyCustomerByVerificationTypeAndValue($verification_type, Request $request)
    {
        $verification_value = $request->input('value');
        if (empty($verification_value)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification value is required.',
                'customer' => null,
                'orders' => [],
            ], 200);
        }
 
        switch ($verification_type) {
            case 'email':
                return $this->verifyByEmail($verification_value);
            case 'phone':
                return $this->verifyByPhone($verification_value);
            case 'otp':
                return $this->generateAndStoreOtp($verification_value);
            default:
                return $this->returnNotFoundResponse("Invalid verification type");
        }
    }

    private function verifyByEmail($email)
    {
        //check in lv system if email exists
        $customerService = new CustomerService();
        $result = $customerService->getCustomerByEmail($email);
        //result is in response. convert it to array
        $result = $result->getData();
        //convert result to array
        $result = json_decode(json_encode($result), true);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => "Customer with email {$email} not found",
                'customer' => null,
                'orders' => [],
            ], 200);
        }
        if (empty($result['customer'])) {
            return response()->json([
                'success' => false,
                'message' => "Customer with email {$email} not found",
                'customer' => null,
                'orders' => [],
            ], 200);
        }
        if ($result["success"] && !empty($result['customer'])) {
            //take orders by customer email 
            $ordersResult = $this->getOrdersByCustomerEmail($email);
            $ordersResult = $ordersResult->getData();
            //convert to array
            $ordersResult = json_decode(json_encode($ordersResult), true);
            return $ordersResult;
            return response()->json([
                'success' => true,
                'message' => "Customer with email {$email} found",
                'customer' => $result['customer'],
                'orders' => $ordersResult['orders'] ?? [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => "Customer with email {$email} found",
            'customer' => $result['customer'],
            'orders' => $ordersResult['orders'] ?? [],
        ], 200);
    }

    private function verifyByPhone($phone)
    {
        //check in lv system if phone exists
        //if phone wihtout + is given, add +
        if (substr($phone, 0, 1) != '+') {
            $phone = '+' . $phone;
        }
        $customerService = new CustomerService();
        $result = $customerService->getCustomerByPhone($phone);

        $result = json_decode(json_encode($result->getData()), true);


        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => "Customer with phone {$phone} not found. API error",
                'customer' => null,
                'orders' => [],

            ], 200);
        }
        if (empty($result['user'])) {
            //if no userby phone is found, ask to enter email
            return response([
                'success' => false,
                'message' => "Customer with phone {$phone} not found. Please enter your email to continue.",
                'customer' => null,
                'orders' => [],

            ], 200);
        }
        $getOrdersResult = $this->getOrdersByCustomerEmail($result['user']['email'] ?? '');
        $getOrdersResult = $getOrdersResult->getData();
        $getOrdersResult = json_decode(json_encode($getOrdersResult), true);

        return response()->json([
            'success' => true,
            'message' => "Customer with phone {$phone} found",
            'customer' => $result['user'] ?? null,
            'orders' => $getOrdersResult['orders'] ?? [],

        ], 200);
    }

    private function getOrdersByCustomerEmail($email)
    {
        //check in lv system if email exists
        $customerService = new CustomerService();
        $result = $customerService->getOrdersByCustomerEmail($email);
        $result = json_decode(json_encode($result->getData()), true);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => "Orders not found for customer with email {$email}",
                'customer' => null,
                'orders' =>  [],
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => "Orders found for customer with email {$email}",
            'customer' => ['email' => $result['customer']['email'] ?? ""],
            'orders' => $result['orders'] ?? [],
        ], 200);
    }

    private function generateAndStoreOtp($email)
    {
        //
        $url = env("APP_LOGIN_URL", "https://login.baaboo.com") . "/api/create-user-email-otp";
        $response = Http::withoutVerifying()->post($url, [
            'email' => $email,
        ]);
        if ($response->successful() && isset($response['success']) && $response['success']) {
            return response()->json([
                'success' => true,
                'opt_status' => $response['success'],
                'message' => "OTP sent to email {$email}",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'opt_status' => false,
                'message' => "Failed to send OTP to email {$email}",
            ], 200);
        }
    }


    public function verifyEmailByOtp(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');

        if (empty($email) || empty($otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Email and OTP are required.',
            ], 400);
        }


        $url = env("APP_LOGIN_URL", "https://login.baaboo.com") . "/api/verify-user-email-otp";
        $response = Http::withoutVerifying()->post($url, [
            'email' => $email,
            'otp' => $otp,
        ]);
        if ($response->successful() && isset($response['success']) && $response['success']) {
            //take order of customer by email
            $ordersResult = $this->getOrdersByCustomerEmail($email);
            $ordersResult = $ordersResult->getData();
            //convert to array
            $ordersResult = json_decode(json_encode($ordersResult), true);

            return response()->json([
                'success' => true,
                'message' => "OTP verified for email {$email}",
                'customer' => $ordersResult['customer'] ?? null,
                'orders' => $ordersResult['orders'] ?? [],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "OTP verification failed for email {$email}",
                'customer' => null,
                'orders' => [],
            ], 200);
        }
    }

    private function returnNotFoundResponse($message = "Customer not found")
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 200);
    }
}
