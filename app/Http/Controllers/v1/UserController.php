<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * API of List User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $users
     */
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'per_page'    => 'required|numeric',
            'page'       => 'required|numeric',
            'sort_field' => 'nullable|string',
            'sort_order' => 'nullable|in:asc,desc',
            'first_name' => 'nullable|string',
        ]);
        $users   = User::query();
        //sorting
        if ($request->sort_field && $request->sort_order) {
            $users  =  $users->orderBy($request->sort_field, $request->sort_order);
        } else {
            $users  =  $users->orderBy('id', 'DESC');
        }
        //searching
        if (isset($request->first_name)) {
            $users->where("first_name", "LIKE", "%{$request->first_name}%");
        }
        //pagination
        $per_page = $request->per_page;
        $page    = $request->page;
        $users   = $users->skip($per_page * ($page - 1))->take($per_page);
        return response()->json([
            "success" => true,
            "message" => "User list",
            'data'    => $users->get()
        ]);
    }
    /**
     * API of Create User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $user
     */
    public function create(Request $request)
    {
        //validation code
        $this->validate($request, [
            'first_name'      => 'required|string',
            'first_name'      => 'required|string',
            'email'           => 'required|email',
            'password'        => 'required|string',
            'code'            => 'required|string|min:6',
            'type'            => 'in:user,admin,superadmin',
            'is_active'       => 'nullable|boolean',
            'is_first_login'  => 'nullable|string',
            'roles.*.role_id' => 'required|string',

        ]);
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->only('first_name', 'last_name', 'password', 'email', 'is_first_login', 'is_active', 'code', 'type'));
        $user->roles()->attach($request->roles);
        return response()->json([
            "success" => true,
            "message" => "User Created successfully.",
            "data"    => $user->load('roles')
        ]);
    }
    /**
     * API of get perticuler user details
     *
     * @param  $id
     * @return $user
     */
    public function view($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "User retrieved successfully.",
            "data"    => $user
        ]);
    }
    /**
     * API of Update User
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        //validation code
        $this->validate($request, [
            'first_name'      => 'required|string',
            'first_name'      => 'required|string',
            'email'           => 'required|email',
            'password'        => 'required|string',
            'code'            => 'required|string|min:6',
            'type'            => 'in:user,admin,superadmin',
            'is_active'       => 'nullable|boolean',
            'is_first_login'  => 'nullable|string',
            'roles.*.role_id' => 'required|string'
        ]);
        $users = User::findOrFail($id);
        $request['password'] = Hash::make($request->password);
        $users->update($request->only('first_name', 'last_name', 'email', 'is_active', 'password', 'is_first_login', 'code', 'type'));
        $users->roles()->sync($request->roles);
        return response()->json([
            "success" => true,
            "message" => "User Updated successfully.",
        ]);
    }
    /**
     * API of Delete User
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
        $users = User::findOrFail($id);
        if ($request->soft_delete) {
            if ($users->roles()->count() > 0) {
                $users->roles()->delete();
            }
            $users->delete();
        } else {
            if ($users->roles()->count() > 0) {
                $users->roles()->forceDelete();
            }
            $users->forceDelete();
        }
        return response()->json([
            "success" => true,
            "message" => "User deleted successfully.",
        ]);
    }
    /**
     * API of Restore User
     *
     * @param  $id
     */
    public function restoreData($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "User Restored successfully.",
        ]);
    }
}
