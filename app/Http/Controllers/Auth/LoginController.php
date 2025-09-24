<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }



    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCustomerLoginForm(Request $request)
    {
        // $brand = \App\Services\BrandService::detectBrandHost($request->getHost());
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('auth.customer-login');
    }

    public function customerLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $credentials = $request->only('email', 'password');

       
        if (Auth::guard('customer')->attempt($credentials)) {
            // Authentication passed...
            // dd(Auth::guard('customer')->check())

            return redirect()->route('customer.dashboard');
        }


        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ]);
    }

    public function customer_logout(Request $request)
    {
        Auth::guard('customer')->logout(); //   logout from 'customer' guard

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); //   Redirect to base URL
    }





    protected function authenticated(Request $request, $user)
    {
        // If the user has no roles at all...
        if ($user->roles()->count() === 0) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'role' => 'Your account has not been assigned any role. Please contact the administrator.',
                ]);
        }
    }
}
