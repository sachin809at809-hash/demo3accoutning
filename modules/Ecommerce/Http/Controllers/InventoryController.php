<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display the inventory overview dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $company_id = company_id();

        // Calculate dynamic metrics from the database
        $variants = \Modules\Ecommerce\Models\EcommerceProductVariant::where('company_id', $company_id)->get();

        $totalValue = $variants->sum(function($variant) {
            return $variant->price * $variant->stock_quantity;
        });

        // Assuming cost is roughly 60% of price for demonstration since cost isn't in DB yet
        $totalCost = $totalValue * 0.60;

        $metrics = [
            'total_value' => $totalValue,
            'total_cost' => $totalCost,
            'total_skus' => $variants->count(),
            'total_quantity' => $variants->sum('stock_quantity'),
        ];

        $health = [
            'out_of_stock' => $variants->where('stock_quantity', '<=', 0)->count(),
            'low_stock' => $variants->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count(),
            'dead_stock' => 0, // Placeholder until order history logic is integrated
        ];

        return view('ecommerce::inventory.index', compact('metrics', 'health'));
    }
}
