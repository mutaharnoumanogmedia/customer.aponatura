<?php

namespace App\Services;

use App\Models\MagicLink;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class MagicLinkService
{
    public function create(User $user, $link_validity = 30)
    {
        // Delete any existing magic links for this user
        // MagicLink::where('user_id', $user->id)->delete(); // this will be in lv.system

        // Create new magic link
        $token = md5(Str::random(32));

        $expiresAt = Carbon::now()->addMinutes($link_validity);

        $magicLink = MagicLink::create([
            'token' => $token,
            'user_id' => $user->id,
            'email' => $user->email ?? null,
            'expires_at' => $expiresAt,
        ]);

        //call of waren system api to store hash in that system

        return URL::temporarySignedRoute(
            'magic-link.verify',
            $expiresAt,
            ['token' => $token]
        );
    }

    public function verify($token)
    {

        $magicLink = MagicLink::where('token', $token)->first();

        if (!$magicLink || !$magicLink->isValid()) {
            return null;
        }
        // Mark the link as used

        //will Uncomment this when we have the waren system api
        // $magicLink->update(['used' => true]);

        return $this->magicLinkUser($magicLink->email);
    }

    public function magicLinkUser($email)
    {


        $userResponse = (new CustomerService())->getCustomerByEmail($email);
    //    dd("User response from API: ", $userResponse);
      
        $userData = $userResponse->getData()->customer;
        // dd("User data from API: ", $userData);   

        // Optionally convert to User model (in-memory, not saved)
        return new \App\Models\User(
            [
                'id' => $userData->id,
                'name' => $userData->first_name . " " . $userData->last_name,
                'email' => $userData->email,
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'phone' => $userData->phone ?? null,
                'avatar' => $userData->avatar ?? null,
                'address' => $userData->address ?? null,
                'postal_code' => $userData->postal_code ?? null,
                'city' => $userData->city ?? null,
                'country' => $userData->country ?? null
            ]
        );
    }




    public function setSupportMagicLinkUser($email)
    {
        try {
            $host = request()->getHost(); // e.g., customer.biovana.com

            $baseDomain = Str::after($host, '.');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env("APP_SUPPORT_URL"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('action' => 'createMagicLinkAPICall', 'api_key' => '1e4602bd9af3a88a4cf77b8084453652', 'email' => $email, 'brand_domain' => $baseDomain),

            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // echo $response;
            // dd($response);
            $data = json_decode($response, true);
            $magicLink = $data['magicLink'] ?? "";

            // This method can be used to set the user in the session or any other logic needed
            session(['support_magic_link' => $magicLink]);
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            Log::error('Error setting support magic link user: ' . $e->getMessage());
        }
    }


    public function getBaabooBooksMagicLink($email, $first_name)
    {
        $response = Http::withToken(env("BAABOO_BOOKS_API_TOKEN"))
            ->post(env("APP_BAABOO_BOOKS_URL"), [
                'email' => $email,
                'firstname' => $first_name,
                // 'lastname' => $request->input('lastname'), // optional
                // 'wawi_customer_number' => $request->input('wawi_customer_number'), // optional
                'task' => 'create'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $magicLink = $data['response']['magic-link'];

            // Store magic link in session
            Session::put('baaboo_books_magic_link', $magicLink);

            return response()->json([
                'message' => 'Magic link stored in session.',
                'magic_link' => $magicLink
            ]);
        }

        return response()->json([
            'message' => 'Failed to get magic link',
            'error' => $response->body()
        ], $response->status());
    }
}
