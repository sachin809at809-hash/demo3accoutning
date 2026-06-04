<?php
namespace Modules\Ecommerce\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Ecommerce\Models\Review;
class ReviewController extends Controller {
    public function index() {
        $reviews = Review::all();
        return view('ecommerce::reviews.index', compact('reviews'));
    }
}