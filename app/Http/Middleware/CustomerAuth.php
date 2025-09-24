<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        // Check if the user is authenticated as a customer
        if (!Auth::guard('customer')->check()) {
            // dd(Session::all());
            return redirect()->route('customer.login.form')->with('error', 'Please log in as customer.');
        }
        return $next($request);
    }
}
