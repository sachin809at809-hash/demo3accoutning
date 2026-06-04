<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceOrder;

class CustomerController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'customers');
        
        // In a real app, you'd join with the users table or a dedicated CRM customers table.
        // For now, we extract unique customers from orders.
        $customers = EcommerceOrder::selectRaw('customer_email as email, MAX(customer_name) as name, COUNT(id) as total_orders, SUM(total) as lifetime_value, MAX(created_at) as last_order')
            ->where('company_id', company_id())
            ->whereNotNull('customer_email')
            ->groupBy('customer_email')
            ->get();
            
        return view('ecommerce::crm.index', compact('customers', 'type'));
    }
}
