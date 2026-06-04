<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceOrder;

class TransactionController extends BaseController
{
    public function index()
    {
        // Get orders that have a total > 0 (as proxies for transactions for now)
        $transactions = EcommerceOrder::where('company_id', company_id())
            ->where('total', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ecommerce::transactions.index', compact('transactions'));
    }
}
