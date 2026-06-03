<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Abstracts\Http\Controller;

class BaseController extends Controller
{
    public function assignPermissionsToController()
    {
        $controller = 'ecommerce-main';

        $this->middleware('permission:create-' . $controller)->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-' . $controller)->only('index', 'show', 'edit', 'export', 'load', 'save');
        $this->middleware('permission:update-' . $controller)->only('update', 'enable', 'disable', 'updateStatus', 'syncWooCommerce');
        $this->middleware('permission:delete-' . $controller)->only('destroy');
    }
}
