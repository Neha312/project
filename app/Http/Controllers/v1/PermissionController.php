<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PermissionController extends Controller
{
    public function list()
    {
        $permission = Permission::get();
        $modules = Module::get();
        return response()->json([
            "success" => true,
            "message" => "Permission List.",
            "data" => $permission, $modules
        ]);
    }
    public function view()
    {
        $permissions = Permission::all();
        return response()->json([
            "success" => true,
            "message" => "Permission List.",
            'data'    => $permissions
        ]);
    }
    public function create(Request $request)
    {

        $this->validate($request, [
            'name'                     => 'required|string',
            'description'              => 'required|string',
            'is_active'                => 'nullable|boolean',
            'modules.*'                => 'required|array',
            'modules.*.module_id'     => 'required|string',
            'modules.*.add_access'     => 'required|boolean',
            'modules.*.edit_access'    => 'required|boolean',
            'modules.*.delete_access'  => 'required|boolean',
            'modules.*.view_access'    => 'required|boolean',
        ]);
        $permission = Permission::create($request->only('name', 'description',));
        $permission->modulepermission()->createMany($request->modules);
        return response()->json([
            "success" => true,
            "message" => "Permission created successfully.",
            "data" => $permission
        ]);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'                     => 'required|string',
            'description'              => 'required|string',
            'is_active'                => 'nullable|boolean',
        ]);
        Permission::findOrFail($id)->update($request->only('name', 'description', 'is_active'));
        return response()->json([
            "success" => true,
            "message" => "Permission Updated successfully.",
        ]);
    }
    public function delete($id)
    {
        Permission::findOrFail($id)->delete();
        return response()->json([
            "success" => true,
            "message" => "Permission deleted successfully.",
        ]);
    }
}
