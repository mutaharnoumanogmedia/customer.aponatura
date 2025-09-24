<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;



    function getActiveGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (\Auth::guard($guard)->check()) {
                $guard = $guard === 'web' ? 'admin' : $guard; // Adjusting for the 'web' guard
                return $guard;
            }
        }
        return null;
    }
}
