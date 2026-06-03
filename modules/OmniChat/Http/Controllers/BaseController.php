<?php

namespace Modules\OmniChat\Http\Controllers;

use App\Abstracts\Http\Controller;

class BaseController extends Controller
{
    public function assignPermissionsToController()
    {
        $controller = 'omni-chat-main';

        $this->middleware('permission:create-' . $controller)->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-' . $controller)->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-' . $controller)->only('update', 'enable', 'disable', 'reply');
        $this->middleware('permission:delete-' . $controller)->only('destroy');
    }
}
