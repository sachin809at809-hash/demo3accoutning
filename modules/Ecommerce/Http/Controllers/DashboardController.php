<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Modules\Ecommerce\Models\EcommerceProduct;
use Modules\Ecommerce\Models\EcommerceOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = EcommerceProduct::count();
        
        $orders = EcommerceOrder::all();
        $totalOrders = $orders->count();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $totalRevenue = $orders->whereNotIn('status', ['cancelled'])->sum('total');
        
        $recentOrders = EcommerceOrder::orderBy('created_at', 'desc')->take(5)->get();

        return view('ecommerce::index', compact(
            'totalProducts', 
            'totalOrders', 
            'pendingOrders', 
            'totalRevenue',
            'recentOrders'
        ));
    }
}
