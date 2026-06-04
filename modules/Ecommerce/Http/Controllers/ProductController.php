<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Ecommerce\Models\EcommerceProduct;
use Modules\Ecommerce\Models\EcommerceCategory;

class ProductController extends Controller
{
    public function index()
    {
        $products = EcommerceProduct::with('category')->get();
        return view('ecommerce::products.index', compact('products'));
    }

    public function create()
    {
        $categories = EcommerceCategory::all();
        return view('ecommerce::products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = EcommerceProduct::create([
            'company_id' => company_id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Create the default inventory variant
        \Modules\Ecommerce\Models\EcommerceProductVariant::create([
            'company_id' => company_id(),
            'product_id' => $product->id,
            'name' => 'Default',
            'sku' => strtoupper(Str::random(8)),
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ecommerce/products', 'public');
            \Modules\Ecommerce\Models\EcommerceProductImage::create([
                'company_id' => company_id(),
                'product_id' => $product->id,
                'image_path' => $path,
                'sort_order' => 0
            ]);
        }

        flash('Product created successfully!')->success();

        return redirect()->route('ecommerce.products.index');
    }

    public function edit($id)
    {
        $product = EcommerceProduct::findOrFail($id);
        $categories = EcommerceCategory::all();
        return view('ecommerce::products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = EcommerceProduct::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ecommerce/products', 'public');
            \Modules\Ecommerce\Models\EcommerceProductImage::create([
                'company_id' => company_id(),
                'product_id' => $product->id,
                'image_path' => $path,
                'sort_order' => 0
            ]);
        }

        flash('Product updated successfully!')->success();

        return redirect()->route('ecommerce.products.index');
    }

    public function destroy($id)
    {
        $product = EcommerceProduct::findOrFail($id);
        $product->delete();

        flash('Product deleted successfully!')->success();

        return redirect()->route('ecommerce.products.index');
    }
}
