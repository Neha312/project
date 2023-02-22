<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    // [
    //     'permission_id' => $module->permission_id
    // ],
    // 'module_id' => $module->module_id,
    // 'add_access' => $module->add_access,
    // 'edit_access' => $module->edit_access,
    // 'view_access' => $module->view_access,
    // 'permission_id'   => ModulePermission::modules()->permission_id,
    // 'module_id'     => $request->get('module_id'),
    // 'add_access' => $request->get('add_access'),
    // 'edit_access'    => $request->get("edit_access"),
    // 'view_access'   => $request->get('view_access'),
}
