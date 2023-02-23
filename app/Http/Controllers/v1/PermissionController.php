<?php

namespace App\Http\Controllers\v1;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ModulePermission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * API of List Permission
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $permissions
     */
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'per_page'    => 'required|numeric',
            'page'       => 'required|numeric',
            'sort_field' => 'nullable|string',
            'sort_order' => 'nullable|in:asc,desc',
            'name'       => 'nullable|string',
        ]);
        $permissions = Permission::query();
        //sorting
        if ($request->sort_field && $request->sort_order) {
            $permissions = $permissions->orderBy($request->sort_field, $request->sort_order);
        } else {
            $permissions = $permissions->orderBy('id', 'DESC');
        }
        //searching
        if (isset($request->name)) {
            $permissions->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination
        $per_page     = $request->per_page;
        $page        = $request->page;
        $permissions = $permissions->skip($per_page * ($page - 1))->take($per_page);
        return response()->json([
            "success" => true,
            "message" => "Permission List.",
            "data"    => $permissions->get()
        ]);
    }
    /**
     * API of Create Permission
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $permission
     */
    public function create(Request $request)
    {

        //validation code
        $this->validate($request, [
            'name'                     => 'required|string',
            'description'              => 'required|string',
            'is_active'                => 'nullable|boolean',
            'modules.*'                => 'required|array',
            'modules.*.module_id'      => 'required|string|exists:modules,id',
            'modules.*.add_access'     => 'required|boolean',
            'modules.*.edit_access'    => 'required|boolean',
            'modules.*.delete_access'  => 'required|boolean',
            'modules.*.view_access'    => 'required|boolean',
        ]);
        $permission = Permission::create($request->only('name', 'description',));
        $permission->modules()->createMany($request->modules);
        return response()->json([
            "success" => true,
            "message" => "Permission created successfully.",
            "data" => $permission->load('modules')
        ]);
    }
    /**
     * API of get perticuler permission details
     *
     * @param $id
     * @return $permission
     */
    public function view($id)
    {
        $permission = Permission::with('modules')->findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "Permission retrieved successfully.",
            "data"    => $permission
        ]);
    }
    /**
     * API of Update permission
     *@param  \Illuminate\Http\Request  $request
     *@param $id
     */
    public function update(Request $request, $id)
    {
        //validation code
        $this->validate($request, [
            'name'                     => 'required|string',
            'description'              => 'required|string',
            'is_active'                => 'nullable|boolean',
            'modules.*'                => 'required|array',
            'modules.*.module_id'      => 'required|string|exists:modules,id',
            'modules.*.add_access'     => 'required|boolean',
            'modules.*.edit_access'    => 'required|boolean',
            'modules.*.delete_access'  => 'required|boolean',
            'modules.*.view_access'    => 'required|boolean',
        ]);
        $moduleIds = array_column($request->modules, 'module_id');
        $permission = Permission::findOrFail($id);
        $permission_id = ModulePermission::where('permission_id', $permission->id)->whereNotIn('module_id', $moduleIds);
        if ($permission_id->count() > 0) {
            $permission_id->forceDelete();
        }
        $permission->update($request->only('name', 'description'));
        foreach ($request['modules'] as $module) {
            ModulePermission::updateOrCreate(
                ['permission_id' => $permission->id, 'module_id' => $module['module_id']],
                [
                    'add_access'    => $module['add_access'],
                    'edit_access'   => $module['edit_access'],
                    'delete_access' => $module['delete_access'],
                    'view_access'   => $module['view_access'],
                ]
            );
        }
        return response()->json([
            "success" => true,
            "message" => "Permission Updated successfully.",
        ]);
    }
    /**
     * API of Delete Permission
     *
     *@param  \Illuminate\Http\Request  $request
     *@param $id
     */
    public function delete($id, Request $request)
    {
        //validation
        $this->validate($request, [
            'soft_delete' => 'required|bool'
        ]);
        $permission = Permission::findOrFail($id);
        if ($request->soft_delete) {
            if ($permission->modules()->count() > 0) {
                $permission->modules()->delete();
            }
            $permission->delete();
        } else {
            if ($permission->modules()->count() > 0) {
                $permission->modules()->forceDelete();
            }
            $permission->forceDelete();
        }
        return response()->json([
            "success" => true,
            "message" => "Permission deleted successfully.",
        ]);
    }
    /**
     * API of Restore Permission
     *
     * @param $id
     */
    public function restoreData($id)
    {
        Permission::whereId($id)->withTrashed()->restore();
        ModulePermission::where('permission_id', $id)->withTrashed()->restore();
        return response()->json([
            "success" => true,
            "message" => "Permission Restored successfully.",
        ]);
    }
}
