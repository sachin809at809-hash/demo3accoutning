<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Services\WooCommerceSyncService;

class SettingController extends Controller
{
    public function index()
    {
        return view('ecommerce::settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'woocommerce_url' => 'nullable|url',
            'woocommerce_consumer_key' => 'nullable|string',
            'woocommerce_consumer_secret' => 'nullable|string',
        ]);

        setting()->set([
            'ecommerce.woocommerce.url' => rtrim($request->woocommerce_url, '/'),
            'ecommerce.woocommerce.consumer_key' => $request->woocommerce_consumer_key,
            'ecommerce.woocommerce.consumer_secret' => $request->woocommerce_consumer_secret,
        ]);
        
        setting()->save();

        flash('Settings saved successfully!')->success();
        return redirect()->back();
    }

    public function syncWooCommerce()
    {
        $url = setting('ecommerce.woocommerce.url');
        $key = setting('ecommerce.woocommerce.consumer_key');
        $secret = setting('ecommerce.woocommerce.consumer_secret');

        if (!$url || !$key || !$secret) {
            flash('Please configure WooCommerce API credentials first.')->error();
            return redirect()->back();
        }

        try {
            $service = new WooCommerceSyncService($url, $key, $secret);
            $count = $service->syncProducts();
            
            flash("Successfully synced {$count} products from WooCommerce!")->success();
        } catch (\Exception $e) {
            flash('Failed to sync: ' . $e->getMessage())->error();
        }

        return redirect()->back();
    }
}
