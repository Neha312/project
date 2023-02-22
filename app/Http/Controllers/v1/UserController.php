<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //listing of user function
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'perpage'    => 'required|numeric',
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
        $perpage = $request->perpage;
        $page    = $request->page;
        $users   = $users->skip($perpage * ($page - 1))->take($perpage);
        return response()->json([
            "success" => true,
            "message" => "User list",
            'data'    => $users->get()
        ]);
    }
    //create user function
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
        //encrypt passwod
        $request['password'] = Hash::make($request->password);
        //create user
        $user = User::create($request->only('first_name', 'last_name', 'password', 'email', 'is_first_login', 'is_active', 'code', 'type'));
        $user->roles()->attach($request->roles);
        //send response
        return response()->json([
            "success" => true,
            "message" => "User Created successfully.",
            "data"    => $user->load('roles')
        ]);
    }
    //view particuler user function
    public function view($id)
    {
        //find user
        $user = User::with('roles')->findOrFail($id);
        //send response
        if (is_null($user)) {
            return $this->sendError('Role not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "User retrieved successfully.",
            "data"    => $user
        ]);
    }
    //update user function
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
        //update user
        $users = User::findOrFail($id);
        $request['password'] = Hash::make($request->password);
        $users->update($request->only('first_name', 'last_name', 'email', 'is_active', 'password', 'is_first_login', 'code', 'type'));
        $users->roles()->sync($request->roles);
        //send response
        return response()->json([
            "success" => true,
            "message" => "User Updated successfully.",
        ]);
    }
    //delete user function
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
            //delete user
            $users->delete();
        } else {
            if ($users->roles()->count() > 0) {
                $users->roles()->forceDelete();
            }
            //delete user
            $users->forceDelete();
        }
        //delete roles from pivot table

        //send respponse
        return response()->json([
            "success" => true,
            "message" => "User deleted successfully.",
        ]);
    }
    //restore user function
    public function restoreData($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "User Restored successfully.",
        ]);
    }
}
