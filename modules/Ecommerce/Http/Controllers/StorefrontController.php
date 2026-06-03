<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\EcommercePage;
use Modules\Ecommerce\Models\EcommerceDeliveryZone;

class StorefrontController extends Controller
{
    public function home()
    {
        \Log::info('StorefrontController@home was called!');
        $products = \Modules\Ecommerce\Models\EcommerceProduct::where('is_active', true)->get();
        $categories = \Modules\Ecommerce\Models\EcommerceCategory::where('is_active', true)->get();
        return view('ecommerce::storefront.index', compact('products', 'categories'));
    }

    public function page($slug)
    {
        $page = EcommercePage::query()->where('slug', $slug)->first();
        
        if (!$page) {
            return response('Storefront page not found. Please design it in the Website Builder (E-Commerce -> Pages).', 404);
        }

        // Render the raw HTML/CSS from the GrapesJS editor
        return view('ecommerce::storefront.page', compact('page'));
    }
    
    public function product($slug)
    {
        $product = \Modules\Ecommerce\Models\EcommerceProduct::query()->where('slug', $slug)
            ->where('is_active', true)
            ->first();
            
        if (!$product) {
            return response('Product not found.', 404);
        }
            
        return view('ecommerce::storefront.product', compact('product'));
    }
    
    public function checkout()
    {
        $zones = EcommerceDeliveryZone::query()->where('is_active', true)->get();
        return view('ecommerce::storefront.checkout', compact('zones'));
    }

    public function processCheckout(\Illuminate\Http\Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('ecommerce.store.cart')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'shipping_address' => 'required|string',
            'zone_id' => 'required|integer',
        ]);

        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $zone = EcommerceDeliveryZone::find($request->zone_id);
        $delivery_fee = $zone ? $zone->delivery_fee : 0;
        $total = $subtotal + $delivery_fee;

        // Create the order
        $order = \Modules\Ecommerce\Models\EcommerceOrder::create([
            'company_id' => company_id() ?? 1, // Fallback for public if needed
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'shipping_address' => $request->shipping_address,
            'delivery_zone_id' => $request->zone_id,
            'subtotal' => $subtotal,
            'delivery_fee' => $delivery_fee,
            'total' => $total,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create order items and reduce stock
        foreach ($cart as $id => $details) {
            \Modules\Ecommerce\Models\EcommerceOrderItem::create([
                'company_id' => company_id() ?? 1,
                'order_id' => $order->id,
                'product_id' => $id,
                'product_name' => $details['name'],
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'total' => $details['price'] * $details['quantity'],
            ]);
            
            // Reduce stock quantity
            $product = \Modules\Ecommerce\Models\EcommerceProduct::find($id);
            if ($product) {
                $product->stock_quantity = max(0, $product->stock_quantity - $details['quantity']);
                $product->save();
            }
        }

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('ecommerce.store.index')->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }
}
