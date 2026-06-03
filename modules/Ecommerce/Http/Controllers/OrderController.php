<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceOrder;

class OrderController extends Controller
{
    public function index()
    {
        $orders = EcommerceOrder::orderBy('created_at', 'desc')->get();
        return view('ecommerce::orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = EcommerceOrder::with('items')->findOrFail($id);
        return view('ecommerce::orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = EcommerceOrder::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        flash('Order status updated successfully!')->success();
        return redirect()->back();
    }
}
