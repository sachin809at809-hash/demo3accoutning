<?php

namespace Modules\Ecommerce\Services;

use Illuminate\Support\Facades\Http;
use Modules\Ecommerce\Models\EcommerceProduct;

class WooCommerceSyncService
{
    protected $url;
    protected $consumerKey;
    protected $consumerSecret;

    public function __construct($url, $consumerKey, $consumerSecret)
    {
        $this->url = $url;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    public function syncProducts()
    {
        // Setup basic auth for WP REST API
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->url . '/wp-json/wc/v3/products', [
                'per_page' => 100,
                'status' => 'publish'
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to connect to WooCommerce API. HTTP Status: ' . $response->status());
        }

        $products = $response->json();
        $syncedCount = 0;

        foreach ($products as $wcProduct) {
            // Update or Create the product
            $product = EcommerceProduct::updateOrCreate(
                ['company_id' => company_id() ?? 1, 'name' => $wcProduct['name']], // We match by name for simplicity, ideally by a woo_id column
                [
                    'description' => strip_tags($wcProduct['description']),
                    'price' => $wcProduct['price'] ?: 0.00,
                    'stock_quantity' => $wcProduct['stock_quantity'] ?? ($wcProduct['in_stock'] ? 100 : 0),
                    'is_active' => true,
                ]
            );

            // Sync main image if exists
            if (!empty($wcProduct['images'])) {
                // In a production app, we would download the image using file_get_contents and save it locally.
                // For this MVP, we will just link the external URL, or simulate the download.
                
                $imageUrl = $wcProduct['images'][0]['src'];
                
                // For demonstration, we'll just save the external URL directly into our database
                // (Assuming our Blade views can handle external URLs)
                \Modules\Ecommerce\Models\EcommerceProductImage::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'company_id' => $product->company_id,
                        'image_path' => $imageUrl, // We will update the blade template to handle full URLs
                        'is_primary' => true
                    ]
                );
            }

            $syncedCount++;
        }

        return $syncedCount;
    }
}
