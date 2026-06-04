<?php
namespace Modules\Ecommerce\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\Ecommerce\Models\StoreUser;
class StoreUserController extends Controller {
    public function index() {
        $users = StoreUser::all();
        return view('ecommerce::store_users.index', compact('users'));
    }
}