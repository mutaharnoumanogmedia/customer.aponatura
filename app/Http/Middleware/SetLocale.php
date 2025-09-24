<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            App::setLocale(session('locale', config('app.locale')));
        } catch (\Exception $e) {
            // Handle the exception if needed, e.g., log it or set a default locale
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
