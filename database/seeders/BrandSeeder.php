<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Brand::create([
            'slug' => 'baaboo',
            'name' => 'Baaboo',
            'domain' => 'customer.baaboo.com',
            'logo_path' => 'brands/baaboo/logo.png',
            'favicon_path' => 'brands/baaboo/favicon.ico',
            'primary_color' => '#ff5f00',
            'secondary_color' => '#000000',
            'title' => 'Baaboo Customer Portal',
            'slogan' => 'Pure Ingredients, Powerful Results',
            'support_email' => 'support@biovana.baaboo.com',
            'config' => json_encode([
                'show_digital_products' => true,
                'features' => ['orders', 'support', 'products', 'invoices'],
            ])
        ]);
        Brand::create([
            'slug' => 'biovana',
            'name' => 'Biovana',
            'domain' => 'customer.biovana.com',
            'logo_path' => 'brands/biovana/logo.png',
            'favicon_path' => 'brands/biovana/favicon.ico',
            'primary_color' => '#C76E00',
            'secondary_color' => '#f8f9fa',
            'title' => 'Biovana Customer Portal',
            'slogan' => 'Pure Ingredients, Powerful Results',
            'support_email' => 'support@biovana.baaboo.com',
            'config' => json_encode([
                'show_digital_products' => true,
                'features' => ['orders', 'support', 'products', 'invoices'],
            ])
        ]);


        Brand::create([
            'slug' => 'greentornado',
            'name' => 'Green Tornado',
            'domain' => 'customer.greentornado.com',
            'logo_path' => 'brands/greentornado/logo.png',
            'favicon_path' => 'brands/greentornado/favicon.ico',
            'primary_color' => '#008000',
            'secondary_color' => '#f8f9fa',
            'title' => 'Green Tornado Customer Portal',
            'slogan' => 'Green Tornado Pure Ingredients, Powerful Results',
            'support_email' => 'support@greentornado.com',
            'config' => json_encode([
                'show_digital_products' => true,
                'features' => ['orders', 'support', 'products', 'invoices'],
            ])
        ]);
    }
}
