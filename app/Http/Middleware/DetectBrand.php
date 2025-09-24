<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
use App\Models\Admin\Brand;

class DetectBrand
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       
        $host = $request->getHost(); // e.g., customer.biovana.com

        // Extract brand (assuming subdomain format: customer.brand.com)
        $brand = explode('.', $host)[1] ?? 'baaboo'; // fallback to baaboo
        
        // Check if the brand exists in the database
        $brand = Brand::where('slug', $brand)->active()->first();

        if (!$brand) {
            abort(404, 'Brand not found or inactive');
        }

        // Share brand info globally (views, config, etc.)
        app()->instance('currentBrand', $brand);
        View::share('brand', $brand);

        return $next($request);
    }
}
