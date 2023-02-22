<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    //listing of role function
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'perpage'    => 'required|numeric',
            'page'       => 'required|numeric',
            'sort_field' => 'nullable|string',
            'sort_order' => 'nullable|in:asc,desc',
            'name'       => 'nullable|string',
        ]);
        $roles   = Role::query();
        //sorting
        if ($request->sort_field && $request->sort_order) {
            $roles =  $roles->orderBy($request->sort_field, $request->sort_order);
        } else {
            $roles =  $roles->orderBy('id', 'DESC');
        }
        //searching
        if (isset($request->name)) {
            $roles->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination
        $perpage = $request->perpage;
        $page    = $request->page;
        $roles   = $roles->skip($perpage * ($page - 1))->take($perpage);
        return response()->json([
            "success" => true,
            "message" => "Role List.",
            'data'    => $roles->get()
        ]);
    }
    //create role function
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
    //view particuler role function
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
    //update role function
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
    //delete role function
    public function delete($id, Request $request)
    {
        //validation
        $this->validate($request, [
            'soft_delete' => 'required|bool'
        ]);
        //find role from role table
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
    //restore role function
    public function restoreData($id)
    {
        Role::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "Role Restored successfully.",
        ]);
    }
}
