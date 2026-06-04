<?php
namespace Modules\Ecommerce\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Ecommerce\Models\Brand;
class BrandController extends Controller {
    public function index() {
        $brands = Brand::all();
        return view('ecommerce::brands.index', compact('brands'));
    }
}