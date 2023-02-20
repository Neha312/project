<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //listing of role
    public function list()
    {
        $role = Role::get();
        return response()->json([
            "success" => true,
            "message" => "Role List.",
            'data'    => $role
        ]);
    }
    //create role
    public function create(Request $request)
    {
        //validation code
        $this->validate($request, [
            'name'                         => 'required|string',
            'description'                  => 'required|string',
            'is_active'                    => 'nullable|boolean',
            'permissions.*.permission_id'  => 'required|string',
        ]);
        //create role
        $roles = Role::create($request->only('name', 'description'));
        //create permission into pivot table
        $roles->permissions()->attach($request->permissions);
        //send response
        return response()->json([
            "success" => true,
            "message" => "Role created successfully.",
            'data'    => $roles->load('permissions')
        ]);
    }
    //view role
    public function view($id)
    {
        //find role
        $role = Role::with('permissions')->findOrFail($id);
        //send response
        if (is_null($role)) {
            return $this->sendError('Role not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Role retrieved successfully.",
            "data"    => $role
        ]);
    }
    //update role
    public function update(Request $request, $id)
    {
        //validation code
        $this->validate($request, [
            'name'                         => 'required|string',
            'description'                  => 'required|string',
            'is_active'                    => 'nullable|boolean',
            'permissions.*.permission_id'  => 'required|string',
        ]);
        //update role
        $roles = Role::findOrFail($id);
        $roles->update($request->only('name', 'description'));

        //create permission into pivot table
        $roles->permissions()->sync($request->permissions);
        //send response
        return response()->json([
            "success" => true,
            "message" => "Role Updated successfully.",
        ]);
    }
    //delete role
    public function delete($id, Request $request)
    {
        $this->validate($request, [
            'soft_delete' => 'required|bool'
        ]);
        //find role
        $roles = Role::findOrFail($id);;
        //delete permission from pivot table
        if ($request->soft_delete) {
            if ($roles->permissions()->count() > 0) {
                $roles->permissions()->delete();
            }
            $roles->delete();
        } else {
            if ($roles->permissions()->count() > 0) {
                $roles->permissions()->forceDelete();
            }
            $roles->forceDelete();
        }
        //send response
        return response()->json([
            "success" => true,
            "message" => "Role deleted successfully.",
        ]);
    }
    public function restoreData($id)
    {
        Role::onlyTrashed()->find($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "Role Restored successfully.",
        ]);
    }
}
