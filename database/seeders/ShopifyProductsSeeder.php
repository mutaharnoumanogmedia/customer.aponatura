<?php

namespace Database\Seeders;

use App\Models\Admin\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ShopifyProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $products = [];

        for ($i = 0; $i < 3000; $i++) {
            // simulate Shopify timestamps
            $createdAt     = $faker->dateTimeBetween('-2 years', 'now');
            $updatedAt     = $faker->dateTimeBetween($createdAt, 'now');
            $shopifyGidNum = $faker->unique()->numberBetween(1000000, 9999999);
            $brands = Brand::pluck('name')->toArray();
            $imageUrl = "https://baaboo.com/cdn/shop/files/001_La-Ultra-Vital_1280x.jpg?v=1711102197";


            $products[] = [
                'shopify_id'         => "gid://shopify/Product/{$shopifyGidNum}",
                'handle'             => Str::slug($faker->words(3, true)),
                'title'              => $faker->sentence(3),
                'description'        => $faker->paragraph(),
                'vendor'             => $faker->company(),
                'product_type'       => $faker->word(),
                'tags'               => implode(',', $faker->words(3)),
                'brand'              => $faker->randomElement($brands),
                'show_to_customer'   => $faker->boolean(70),
                'price'              => $faker->randomFloat(2, 1, 500),
                'currency'           => "EUR",
                'image_url'          => $imageUrl,
                'online_store_url'   => $faker->url(),
                'shopify_created_at' => $createdAt,
                'shopify_updated_at' => $updatedAt,
                'raw_data'           => json_encode([
                    'title' => $faker->sentence(),
                    'meta'  => $faker->words(3)
                ]),
                'created_at'         => now(),
                'updated_at'         => now(),
            ];

            // insert in batches of 500
            if (count($products) === 500) {
                DB::table('shopify_products')->insert($products);
                $products = [];
            }
        }

        // insert any remaining
        if (count($products) > 0) {
            DB::table('shopify_products')->insert($products);
        }
    }
}
