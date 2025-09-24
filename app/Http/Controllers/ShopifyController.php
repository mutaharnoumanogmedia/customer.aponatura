<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopifyController extends Controller
{
    //
    public function redirectToShopify(Request $request)
    {
        $shop = $request->get('shop'); // e.g., og-testshop.myshopify.com

        $redirectUri = urlencode(config('app.url') . '/shopify/callback');
        $scopes = config('shopify.scopes');
        $apiKey = config('shopify.api_key');

        $oauthUrl = "https://{$shop}/admin/oauth/authorize?client_id={$apiKey}&scope={$scopes}&redirect_uri={$redirectUri}";

        return redirect()->away($oauthUrl);
    }

    public function callback(Request $request)
    {
        $shop = $request->get('shop');
        $code = $request->get('code');
        $hmac = $request->get('hmac');

        $response = Http::asForm()->post("https://{$shop}/admin/oauth/access_token", [
            'client_id' => config('shopify.api_key'),
            'client_secret' => config('shopify.api_secret'),
            'code' => $code,
        ]);

        $data = $response->json();

        // Store this access token in DB (per-shop)
        $accessToken = $data['access_token'];

        return response()->json([
            'shop' => $shop,
            'access_token' => $accessToken
        ]);
    }
}
