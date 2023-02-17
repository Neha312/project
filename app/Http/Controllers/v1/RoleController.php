<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class RoleController extends Controller
{
    public function list()
    {
        $role = Role::get();
        $permission = Permission::get();
        return response()->json([
            "success" => true,
            "message" => "Role List.",
            'data'    => $role, $permission
        ]);
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'name'                         => 'required|string',
            'description'                  => 'required|string',
            'is_active'                    => 'nullable|boolean',
            'permissions.*.permission_id'  => 'required|string',

        ]);
        $role = Role::create($request->only('name', 'description'));
        $role->permissions()->attach($request->permissions);
        return response()->json([
            "success" => true,
            "message" => "Role deleted successfully.",
            'data' => $role
        ]);
    }
    public function view()
    {
        $roles = Role::all();
        return response()->json([
            "success" => true,
            "message" => "Role List.",
            'data'    => $roles
        ]);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'        => 'required|string',
            'description' => 'required|string',
            'is_active'   => 'nullable|boolean',
        ]);
        Role::findOrFail($id)->update($request->only('name', 'description', 'is_active'));
        return response()->json([
            "success" => true,
            "message" => "Role Updated successfully.",
        ]);
    }
    public function delete($id)
    {
        Role::findOrFail($id)->delete();
        //  $role=Role::findOrFail($id);;
        // if ($role->permissions()->count() > 0) {
        //     $role->permissions()->delete();
        // }
        // $role->delete();
        return response()->json([
            "success" => true,
            "message" => "Role deleted successfully.",
        ]);
    }
}
