<?php
namespace Modules\Ecommerce\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Ecommerce\Models\Issue;
class IssueController extends Controller {
    public function index() {
        $issues = Issue::all();
        return view('ecommerce::issues.index', compact('issues'));
    }
}