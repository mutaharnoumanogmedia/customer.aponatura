<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Http;

class CustomApiUserProvider implements UserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $userResponse = (new CustomerService())->getCustomerByEmail($credentials["email"]);
        
        $userResponse = $userResponse->getData();
       
        if ($userResponse && isset($userResponse->customer)) {
            $customer = $userResponse->customer ?? null;

            if ($customer) {
                // dd( "Customer found by  credentials: ", $customer);
                $customer = [
                    'id' => $customer->id,
                    'name' => $customer->first_name . " " . $customer->last_name,
                    'email' => $customer->email,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'phone' => $customer->phone ?? null,
                    'avatar' => $customer->avatar ?? null,
                    'address' => $customer->address ?? null,
                    'postal_code' => $customer->postal_code ?? null,
                    'city' => $customer->city ?? null,
                    'country' => $customer->country ?? null
                ];

                return new Customer($customer); // Treat this like an in-memory user

            }
        }

        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Assume login via API validated already
        return true;
    }

    // These can return null or minimal logic if you only log in once


    public function retrieveById($identifier)
    {
        $userResponse = (new CustomerService())->getCustomerById($identifier);
      
    //    dd("User response from API , function retrieveById: ", $userResponse->getData() );
       $userResponse = $userResponse->getData();
        if ($userResponse && isset($userResponse->customer)) {
            $customer = $userResponse->customer ?? null;
            if ($customer) {
                
                $customer = [
                    'id' => $customer->id,
                    'name' => $customer->first_name . " " . $customer->last_name,
                    'email' => $customer->email,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'phone' => $customer->phone ?? null,
                    'avatar' => $customer->avatar ?? null,
                    'address' => $customer->address ?? null,
                    'postal_code' => $customer->postal_code ?? null,
                    'city' => $customer->city ?? null,
                    'country' => $customer->country ?? null
                ];
                if ($customer) {

                    //converting to Customer model

                    return new Customer($customer); // Treat this like an in-memory user
                }
            }
        }

        return null;
    }
    public function retrieveByToken($identifier, $token) {}
    public function updateRememberToken(Authenticatable $user, $token) {}
}
