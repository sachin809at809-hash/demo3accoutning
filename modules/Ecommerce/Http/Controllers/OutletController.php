<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Display the outlets and locations dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $outlets = [
            ['name' => 'Main Warehouse', 'location' => 'New York, NY', 'status' => 'active', 'stock_count' => 12450],
            ['name' => 'Downtown Retail Store', 'location' => 'Manhattan, NY', 'status' => 'active', 'stock_count' => 3200],
            ['name' => 'West Coast Distribution', 'location' => 'Los Angeles, CA', 'status' => 'inactive', 'stock_count' => 0],
        ];

        return view('ecommerce::outlets.index', compact('outlets'));
    }
}
