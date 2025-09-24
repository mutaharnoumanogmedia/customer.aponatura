<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Admin\Brand;

class BrandService
{
    public function __construct()
    {
        // Initialize any dependencies here
    }

    public static function detectBrandHost($host)
    {
        // e.g., customer.biovana.com

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
        return $brand;
    }
}
