<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceProduct;

class PosController extends BaseController
{
    public function index()
    {
        // Load products for the POS screen
        $products = EcommerceProduct::where('company_id', company_id())
            ->where('is_active', true)
            ->get();
            
        return view('ecommerce::pos.index', compact('products'));
    }
}
