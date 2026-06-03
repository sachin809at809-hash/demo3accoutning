<?php

namespace Modules\WooCommerce\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Main extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('woo-commerce::index');
    }

    /**
     * Store the WooCommerce API settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'store_url' => 'required|url',
            'consumer_key' => 'required|string',
            'consumer_secret' => 'required|string',
        ]);

        setting()->set([
            'woo-commerce.store_url' => $request->get('store_url'),
            'woo-commerce.consumer_key' => $request->get('consumer_key'),
            'woo-commerce.consumer_secret' => $request->get('consumer_secret'),
        ]);
        
        setting()->save();

        flash('WooCommerce settings saved successfully!')->success();

        return redirect()->route('woo-commerce.index');
    }
    
    /**
     * Trigger a manual sync.
     */
    public function sync()
    {
        $companyId = session('company_id');
        
        if ($companyId) {
            \Modules\WooCommerce\Jobs\SyncOrders::dispatch($companyId);
            flash('WooCommerce sync triggered!')->success();
        } else {
            flash('Company ID not found.')->error();
        }
        
        return redirect()->route('woo-commerce.index');
    }
}
