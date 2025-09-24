<?php

namespace App\Services;

use App\Models\Admin\Brand;
use App\Models\Admin\ShopifyProduct;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyGraphQLService
{
    protected $url;
    protected $token;

    public function __construct()
    {
        $this->url = config('services.shopify.api_url');
        $this->token = config('services.shopify.api_token');
    }

    public function queryProducts($variables = [])
    {
        $query  = $this->getQueryProducts();
        $payload = ['query' => $query];

        // only include variables when you actually have some:

        if (! empty($variables)) {
            $payload['variables'] = $variables;
        }
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->token,
            'Content-Type' => 'application/json',
        ])
            ->withoutVerifying()
            ->post($this->url, $payload);

        return $response->json();
    }
    public function formatQueryProductsAsArray()
    {
        $response = $this->queryProducts();

        // extract the nodes into a simple array
        $products = [];
        if (isset($response['data']['products']['edges'])) {
            foreach ($response['data']['products']['edges'] as $edge) {
                $products[] = $edge['node'];
            }
        }

        // pass to your view
        return  $products;
    }

    protected function getQueryProducts()
    {
        return          '
                 {
                    
                    products(first: 10) {
                    edges {
                        node {
                        id
                        priceRange {
                            minVariantPrice {
                            amount
                            currencyCode
                            }
                        }
                        onlineStorePreviewUrl
                        title
                        featuredMedia {
                            preview {
                            image {
                                originalSrc
                            }
                            }
                        }
                        }
                    }
                    }
                }
                ';
    }






    public function syncProductsToDatabase($brandName = null)
    {

        if (!is_null($brandName)) {
            $brand = Brand::where('name', $brandName)->first();
            if (!$brand) {
                Log::error("Brand not found: " . $brandName);
                return [
                    'success' => false,
                    'message' => 'Brand not found.',
                ];
            }

            if (!$brand->shopify_api_url || !$brand->shopify_api_token) {
                Log::error("Shopify API credentials not set for brand: " . $brand->name);
                return [
                    'success' => false,
                    'message' => 'Shopify API credentials not set for this brand.',
                ];
            }
            $this->url = $brand->shopify_api_url;
            $this->token = $brand->shopify_api_token;
        }
        try {
            $nodes = $this->formatQueryProductsAsArray();
            foreach ($nodes as $node) {
                ShopifyProduct::updateOrInsert(
                    ['shopify_id' => $node['id']],
                    [
                        'handle'           => $node['handle']              ?? null,
                        'title'            => $node['title']               ?? null,
                        'price'            => $node['priceRange']['minVariantPrice']['amount']      ?? 0,
                        'currency'         => $node['priceRange']['minVariantPrice']['currencyCode'] ?? null,
                        'online_store_url' => $node['onlineStorePreviewUrl'] ?? null,
                        'image_url'        => $node['featuredMedia']['preview']['image']['originalSrc'] ?? null,
                        'raw_data'         => json_encode($node),

                        'created_at' => now(),
                        'updated_at' => now(),
                        'brand'            => $brand->name,
                    ]
                );
            }
            Log::info("Successfully synced Shopify $brandName products.");
            return [
                'success' => true,
                'message' => 'Products synced successfully from Shopify ' . $brandName . '.',
                'data'    => ShopifyProduct::where('brand', $brandName)->get(),
            ];
        } catch (\Exception $e) {
            Log::error("Error syncing Shopify $brandName products: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to sync products from Shopify ' . $brandName . '. ' . $e->getMessage(),
                'error'   => $e->getMessage(),
            ];
        }
    }
}
