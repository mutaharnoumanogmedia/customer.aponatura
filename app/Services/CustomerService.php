<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CustomerService
{

    public function getCustomerByEmail($email)
    {

        try {
            // ****** part 1 *******//



            // ****** part 2 *******//

            // Make request to the external API if user is not found in login.baaboo, check from lv.baaboo as if in lp_amazonkunden table



            $url = env("APP_WARENSYS", 'https://lv.test') . "/api/customer-portal/get-customer-by-email/{$email}";
            $response = Http::withoutVerifying()->post($url);



            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json(); // Decode JSON response to array
                // dd("Data from lv.baaboo: ", $data['customer'] ?? null);

                if ($data['success'] && isset($data['customer'])) {
                    $customerData = $data['customer'];
                    // Process as needed (e.g., return or store in DB)
                    $customer = [
                        'id' => $customerData['id'],
                        'first_name' => $customerData['vorname'],
                        'last_name' => $customerData['nachname'],
                        'address' => $customerData['adresse'],
                        'postal_code' => $customerData['plz'],
                        'city' => $customerData['ort'],
                        'country' => $customerData['country'],
                        'email' => $customerData['email']
                    ];
                    // dd("Customer found in lv.baaboo: ", $customer);

                    // //api call to register the user in login.baaboo
                    // $urlRegisterUser = env("APP_LOGIN_URL", 'https://login.baaboo.com') . "/api/create-user";
                    // $responseRegisterUser = Http::withoutVerifying()->withHeaders([
                    //     'Token' =>  env('APP_LOGIN_TOKEN')
                    // ])->post($urlRegisterUser, [
                    //     'email' => $customerData['email'],
                    //     'first_name' => $customerData['vorname'],
                    //     'last_name' => $customerData['nachname'],
                    //     'address' => $customerData['adresse'],
                    //     'postal_code' => $customerData['plz'],
                    //     'city' => $customerData['ort'],
                    //     'country' => $customerData['country']
                    // ]);

                    // Check if the request was successful
                    // if ($responseRegisterUser->successful()) {
                    //     $data = $responseRegisterUser->json(); // Decode JSON response to array
                    //     // dd("Data from register user response: ", $data );
                    //     return response()->json([
                    //         'success' => true,
                    //         "data" => $data,
                    //         "message" => "Customer found in lv.baaboo and registered in login.baaboo"
                    //     ]);
                    //     //get user from response
                    //     $user = $data['user'] ?? null;
                    //     if ($user) {
                    //         $customer['id'] = $user['id'];
                    //     }
                    // }


                    return response()->json([
                        'success' => true,
                        "customer" => $customer,
                        "message" => "Customer found in lv.baaboo"
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer not found or response malformed.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API request failed. ' . $response->body(),
                    'code' => $response->status(),

                ], $response->status());
            }
        } catch (Exception $e) {
            return response([
                "success" => false,
                "message" => $e->getMessage(),
                'code' => 500,
                'line' => $e->getLine(),

            ], 500);
        }
    }




    public function getCustomerById($id)
    {
        try {



            // ****** part 2 *******//

            // Make request to the external API if user is not found in login.baaboo, check from lv.baaboo as if in lp_amazonkunden table
            $url = env("APP_WARENSYS", 'https://lv.test') . "/api/customer-portal/get-customer-by-id/{$id}";

            // Make request to the external API
            $response = Http::withoutVerifying()->post($url);


            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json(); // Decode JSON response to array

                if ($data['success'] && isset($data['customer'])) {
                    $customerData = $data['customer'];
                    // Process as needed (e.g., return or store in DB)
                    $customer = [
                        'id' => $customerData['id'],
                        'first_name' => $customerData['vorname'],
                        'last_name' => $customerData['nachname'],
                        'address' => $customerData['adresse'],
                        'postal_code' => $customerData['plz'],
                        'city' => $customerData['ort'],
                        'country' => $customerData['country'],
                        'email' => $customerData['email']
                    ];

                    return response()->json([
                        'success' => true,
                        "customer" => $customer,
                        "message" => "Customer found in lv.baaboo"
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer not found or response malformed.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API request failed.',
                    'code' => $response->status(),

                ], $response->status());
            }
        } catch (Exception $e) {
            return response([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    public function getCustomersFromApi($skip = 0, $take = 100)
    {
        // Cache key and TTL (in minutes)
        $cacheKey = 'warensys:all_customers';
        $ttl = 60; // cache for 60 minutes

        return Cache::remember($cacheKey, $ttl, function () use ($skip, $take) {
            $url = env('APP_WARENSYS') . '/api/customer-portal/get-all-customers/' . $skip . '/' . $take;
            $response = Http::withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return
                    [
                        'success' => true,
                        'customers' => $data['customers'] ?? [],
                        'total' => $data['total'] ?? 0,
                    ];
            }

            // On failure, return an empty array (or you could throw)
            return [
                'success' => false,
                'error' => $response->json()['error'] ?? 'Unknown error occurred.'
            ];
        });
    }



    public function getOrdersByCustomerEmail($email)
    {
        try {
            $url = env("APP_WARENSYS", 'https://lv.test') . "/api/customer-portal/customer-orders/{$email}?skip=0&take=10";
            $response = Http::withoutVerifying()->get($url);

            // Check if the request was successful
            if ($response->successful()) {
                $data = $response->json(); // Decode JSON response to array

                if ($data['success'] && isset($data['orders'])) {
                    $orders = $data['orders'];
                    return response()->json([
                        'success' => true,
                        "orders" => $orders,
                        "customer" => $data['customer'] ?? null,
                        "message" => "Orders found for customer with email {$email}"
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No orders found for this customer or response malformed.',
                        'customer' => $data['customer'] ?? null
                    ], 404);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API request failed.',
                    'code' => $response->status(),

                ], $response->status());
            }
        } catch (Exception $e) {
            return response([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }


    public function getCustomerByPhone($phone)
    {
        //verify from login.baaboo if phone exists
        $url = env("APP_LOGIN_URL", 'https://login.baaboo.com') . "/api/get-user-by-phone/{$phone}";
        //making http request to the login.baaboo api with custom TOKEN in header and data in body
        $response = Http::withoutVerifying()->withHeaders([
            'Token' =>  env('APP_LOGIN_TOKEN')
        ])->get($url);
        // Check if the request was successful
        $response = $response->json();

        $response = json_decode(json_encode($response), true);

        if (isset($response['success']) && $response['success']) {
            return response()->json([
                'success' => true,
                'message' => "Customer with phone {$phone} found in login.baaboo",
                'user_email' => $response['user']['email'] ?? null,
                'user' => $response['user'] ?? null
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found by phone'
        ], 404);
    }
}
