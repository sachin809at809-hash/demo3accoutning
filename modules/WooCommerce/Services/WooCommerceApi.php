<?php

namespace Modules\WooCommerce\Services;

use Illuminate\Support\Facades\Http;

class WooCommerceApi
{
    protected $storeUrl;
    protected $consumerKey;
    protected $consumerSecret;

    public function __construct()
    {
        $this->storeUrl = rtrim(setting('woo-commerce.store_url'), '/');
        $this->consumerKey = setting('woo-commerce.consumer_key');
        $this->consumerSecret = setting('woo-commerce.consumer_secret');
    }

    /**
     * Get a configured HTTP client for WooCommerce API.
     */
    protected function client()
    {
        return Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->baseUrl("{$this->storeUrl}/wp-json/wc/v3");
    }

    /**
     * Fetch recent orders from WooCommerce.
     *
     * @param int $perPage
     * @param int $page
     * @return array|null
     */
    public function getOrders($perPage = 10, $page = 1)
    {
        if (empty($this->storeUrl) || empty($this->consumerKey) || empty($this->consumerSecret)) {
            return null;
        }

        $response = $this->client()->get('/orders', [
            'per_page' => $perPage,
            'page' => $page,
            'status' => 'processing,completed',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
