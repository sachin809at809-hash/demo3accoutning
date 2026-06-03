<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommerceCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EcommerceCategory::all();
        return view('ecommerce::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('ecommerce::categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        EcommerceCategory::create([
            'company_id' => company_id(),
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        flash('Category created successfully!')->success();

        return redirect()->route('ecommerce.categories.index');
    }

    public function edit($id)
    {
        $category = EcommerceCategory::findOrFail($id);
        return view('ecommerce::categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = EcommerceCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        flash('Category updated successfully!')->success();

        return redirect()->route('ecommerce.categories.index');
    }

    public function destroy($id)
    {
        $category = EcommerceCategory::findOrFail($id);
        $category->delete();

        flash('Category deleted successfully!')->success();

        return redirect()->route('ecommerce.categories.index');
    }
}
