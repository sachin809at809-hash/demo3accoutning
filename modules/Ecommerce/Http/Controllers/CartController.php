<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceProduct;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('ecommerce::storefront.cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = EcommerceProduct::findOrFail($id);
        
        $quantity = $request->input('quantity', 1);
        
        if ($product->stock_quantity < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->images->first() ? $product->images->first()->image_path : null
            ];
        }

        session()->put('cart', $cart);
        
        // Use flash messaging if the theme supports it, or standard session messages
        session()->flash('success', 'Product added to cart successfully!');
        
        return redirect()->route('ecommerce.store.cart');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
        return redirect()->route('ecommerce.store.cart');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
        return redirect()->route('ecommerce.store.cart');
    }
}
