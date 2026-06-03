<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Ecommerce\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommercePage;

class BuilderController extends Controller
{
    public function index()
    {
        // For MVP, we just load the 'home' page. In a full version, we'd list pages.
        $company_id = company_id() ?: 1;
        $page = EcommercePage::firstOrCreate(
            ['company_id' => $company_id, 'slug' => 'home'],
            ['title' => 'Home Page']
        );
        
        return view('ecommerce::builder', compact('page'));
    }

    public function load($pageId)
    {
        $page = EcommercePage::findOrFail($pageId);
        
        // GrapesJS expects JSON format for loading
        return response()->json([
            'gjs-html' => $page->html,
            'gjs-css' => $page->css,
            'gjs-components' => json_decode($page->components),
            'gjs-styles' => json_decode($page->styles),
        ]);
    }

    public function save(Request $request, $pageId)
    {
        $page = EcommercePage::findOrFail($pageId);
        
        $page->update([
            'html' => $request->input('gjs-html'),
            'css' => $request->input('gjs-css'),
            'components' => json_encode($request->input('gjs-components')),
            'styles' => json_encode($request->input('gjs-styles')),
        ]);
        
        return response()->json(['status' => 'success']);
    }
}
