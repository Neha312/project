<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //listing of permission
    public function list()
    {
        $permission = Permission::get();
        return response()->json([
            "success" => true,
            "message" => "Permission List.",
            "data"    => $permission
        ]);
    }
    //create permission
    public function create(Request $request)
    {

        //validation code
        $this->validate($request, [
            'name'                     => 'required|string',
            'description'              => 'required|string',
            'is_active'                => 'nullable|boolean',
            'modules.*'                => 'required|array',
            'modules.*.module_id'      => 'required|string',
            'modules.*.add_access'     => 'required|boolean',
            'modules.*.edit_access'    => 'required|boolean',
            'modules.*.delete_access'  => 'required|boolean',
            'modules.*.view_access'    => 'required|boolean',
        ]);
        //create permission
        $permission = Permission::create($request->only('name', 'description',));
        //create modules into modulepermission table
        $permission->modules()->createMany($request->modules);
        //send response
        return response()->json([
            "success" => true,
            "message" => "Permission created successfully.",
            "data" => $permission->load('modules')
        ]);
    }
    //view permission
    public function view($id)
    {
        //find permission
        $permission = Permission::with('modules')->findOrFail($id);
        //send response
        if (is_null($permission)) {
            return $this->sendError('Permission not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Permission retrieved successfully.",
            "data"    => $permission
        ]);
    }
    //update permission
    public function update(Request $request, $id)
    {
        //validation code
        $this->validate($request, [
            'name'                     => 'required|string',
            'description'              => 'required|string',
            'is_active'                => 'nullable|boolean',
            'modules.*'                => 'required|array',
            'modules.*.module_id'      => 'required|string',
            'modules.*.add_access'     => 'required|boolean',
            'modules.*.edit_access'    => 'required|boolean',
            'modules.*.delete_access'  => 'required|boolean',
            'modules.*.view_access'    => 'required|boolean',
        ]);
        //update permission
        $permission = Permission::findOrFail($id);
        $permission->update($request->only('name', 'description'));
        // dd($permission->id);
        foreach ($request['modules'] as $module) {
            // dd(['module_id' => $module['module_id']]);
            ModulePermission::updateOrCreate(
                ['permission_id' => $permission->id, 'module_id' => $module['module_id']],
                [
                    'add_access' => $module['add_access'],
                    'edit_access' => $module['edit_access'],
                    'delete_access' => $module['delete_access'],
                    'view_access' => $module['view_access'],
                ]
            );
        }
        return response()->json([
            "success" => true,
            "message" => "Permission Updated successfully.",
        ]);
    }
    //delete permission
    public function delete($id, Request $request)
    {
        $this->validate($request, [
            'soft_delete' => 'required|bool'
        ]);
        $permission = Permission::findOrFail($id);
        if ($request->soft_delete) {
            if ($permission->modules()->count() > 0) {
                $permission->modules()->delete();
            }
            //delete permission
            $permission->delete();
        } else {
            if ($permission->modules()->count() > 0) {
                $permission->modules()->forceDelete();
            }
            //delete permission
            $permission->forceDelete();
        }
        //send response
        return response()->json([
            "success" => true,
            "message" => "Permission deleted successfully.",
        ]);
    }
    public function restoreData($id)
    {
        Permission::onlyTrashed()->find($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "Permission Restored successfully.",
        ]);
    }
}
