<?php

namespace App\Http\Controllers\v1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list()
    {
        $users = User::get();
        $roles = Role::get();
        return response()->json([
            "success" => true,
            "message" => "User list",
            'data'    => $users, $roles
        ]);
    }
    public function create(Request $request)
    {

        $this->validate($request, [
            'first_name'     => 'required|string',
            'first_name'     => 'required|string',
            'email'          => 'required|email',
            'password'       => 'required|string',
            'code'           => 'required|string|min:6',
            'type'           => 'in:user,admin', 'superadmin',
            'is_active'      => 'nullable|boolean',
            'is_first_login' => 'nullable|string',
            'roles.*.role_id' => 'required|string',

        ]);
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->only('first_name', 'last_name', 'password', 'email', 'is_first_login', 'is_active', 'code', 'type'));
        $user->roles()->attach($request->roles);
        return response()->json([
            "success" => true,
            "message" => "User Created successfully.",
            "data"    => $user
        ]);
    }
    public function view()
    {
        $users = User::all();
        return response()->json([
            "success" => true,
            "message" => "User List.",
            'data'    => $users
        ]);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name'      => 'required|string',
            'first_name'      => 'required|string',
            'email'           => 'required|email',
            'password'        => 'required|string',
            'code'            => 'required|string|min:6',
            'type'            => 'in:user,admin, superadmin',
            'is_active'       => 'nullable|boolean',
            'is_first_login'  => 'nullable|string',
        ]);
        User::findOrFail($id)->update($request->only('first_name', 'last_name', 'email', 'is_active', 'password', 'is_first_login', 'code', 'type'));
        return response()->json([
            "success" => true,
            "message" => "User Updated successfully.",
        ]);
    }
    public function delete($id)
    {
        User::findOrFail($id)->delete();
        // $user = User::findOrFail($id);
        // if ($user->roles()->count() > 0) {
        //     $user->roles()->delete();
        // }
        // $user->delete();
        return response()->json([
            "success" => true,
            "message" => "User deleted successfully.",
        ]);
    }
}
