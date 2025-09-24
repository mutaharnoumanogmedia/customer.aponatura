<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\MagicLinkService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MagicLinkEmail;
use App\Services\CustomerService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class MagicLinkController extends Controller
{
    //


    public function send(Request $request)
    {

        try {

            $magicLinkService = new MagicLinkService();

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            $link_validity = $request->has('link_validity') && is_numeric($request->input('link_validity'))
                ? (int)$request->input('link_validity')
                : 30;

            $link_validity = $request->has('link_validity') && is_numeric($request->input('link_validity'))
                ? (int)$request->input('link_validity')
                : 30;

            $response = (new CustomerService())->getCustomerByEmail($request->email); // verify from API in lv.baaboo

            $response = $response->getData();


            if (!isset($response->customer)) {
                return response()->json([
                    'success' => false,
                    'url' => 'https://customer.baaboo.com',
                    'message' => 'We could not find a customer with given email address.'
                ], 200);
            }

            $customerData = $response->customer;

            // $user = (new User)->newInstance($response->customer, true);
            $user = new User([
                'id' => $customerData->id,
                'name' => $customerData->first_name . ' ' . $customerData->last_name,
                'email' => $request->email,
                'password' => bcrypt("123456789")
            ]);

            $magicLink = $magicLinkService->create($user, $link_validity);


            if (!$request->has('link_validity')) {

                try {
                    \App\Jobs\SendMagicLinkEmail::dispatch($user->email, $magicLink);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'url' => env('APP_URL', 'https://customer.aponatura.com'),
                        'message' => 'We were unable to send the magic link email at this time. Please try again shortly.'
                    ], 200);
                }
            }
            return response()->json([
                'success' => true,
                'url' => $magicLink,
                'message' => 'Magic link generated successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'url' => env('APP_URL', 'https://customer.aponatura.com'),
                'message' => 'An error occurred while processing your request. Please try again later.'
            ], 500);
        }
    }
}
