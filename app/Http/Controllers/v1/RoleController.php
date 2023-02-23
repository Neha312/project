<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * API of List Role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $roles
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
        $per_page = $request->per_page;
        $page    = $request->page;
        $roles   = $roles->skip($per_page * ($page - 1))->take($per_page);
        return response()->json([
            "success" => true,
            "message" => "Role List.",
            'data'    => $roles->get()
        ]);
    }
    /**
     * API of Create Role
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $roles
     */
    public function create(Request $request)
    {
        //validation code
        $this->validate($request, [
            'name'                         => 'required|string',
            'description'                  => 'required|string',
            'is_active'                    => 'nullable|boolean',
            'permissions.*.permission_id'  => 'required|string',
        ]);
        $roles = Role::create($request->only('name', 'description'));
        $roles->permissions()->attach($request->permissions);
        return response()->json([
            "success" => true,
            "message" => "Role created successfully.",
            'data'    => $roles->load('permissions')
        ]);
    }
    /**
     * API of get perticuler role details
     *
     * @param  $id
     */
    public function view($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "Role retrieved successfully.",
            "data"    => $role
        ]);
    }
    /**
     * API of Update Role
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        //validation code
        $this->validate($request, [
            'name'                         => 'required|string',
            'description'                  => 'required|string',
            'is_active'                    => 'nullable|boolean',
            'permissions.*.permission_id'  => 'required|string',
        ]);
        $roles = Role::findOrFail($id);
        $roles->update($request->only('name', 'description'));
        $roles->permissions()->sync($request->permissions);
        return response()->json([
            "success" => true,
            "message" => "Role Updated successfully.",
        ]);
    }
    /**
     * API of Delete Role
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id, Request $request)
    {
        //validation
        $this->validate($request, [
            'soft_delete' => 'required|bool'
        ]);
        $roles = Role::findOrFail($id);;
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
        return response()->json([
            "success" => true,
            "message" => "Role deleted successfully.",
        ]);
    }
    /**
     * API of Restore Role
     *
     * @param  $id
     */
    public function restoreData($id)
    {
        Role::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "Role Restored successfully.",
        ]);
    }
}
