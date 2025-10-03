<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Stevebauman\Location\Facades\Location;

class LocaleService
{

    public function detectAndSetLocale($ip)
    {
        if (!Session::has('locale')) {
            Session::put('locale', 'de');
            // $location = Location::get($ip);
            // $country = $location->countryName ?? "Unknown";
            // // Log::info("Detected country: " .     $country);
            // // Log::info("IP: " .  $ip);

            // if (in_array($country, ['Germany', 'Austria', 'Switzerland'])) {
            //     App::setLocale('de');
            //     Session::put('locale', 'de');
            // } else {
            //     App::setLocale('en');
            //     Session::put('locale', 'en');
            // }
        } else {
            App::setLocale(Session::get('locale'));
        }
    }


    public static function setLocale($locale)
    {
        session(['locale' => $locale]); // persist locale
        App::setLocale($locale);        // apply temporarily for this request

        echo " Locale set to: " . App::getLocale() . "<br>";
        echo " Session saved as: " . session('locale') . "<br>";
        echo " Translated: " . __('Customer Portal') . "<br>";
    }
}
