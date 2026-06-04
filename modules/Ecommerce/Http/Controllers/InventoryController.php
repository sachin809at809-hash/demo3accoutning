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
        // For Milestone 2: Stubbed metrics to match the 'Blanxer' design UI
        $metrics = [
            'total_value' => 24500.50,
            'total_cost' => 18200.00,
            'total_skus' => 1240,
            'total_quantity' => 18450,
        ];

        $health = [
            'out_of_stock' => 12,
            'low_stock' => 45,
            'dead_stock' => 8,
        ];

        return view('ecommerce::inventory.index', compact('metrics', 'health'));
    }
}
