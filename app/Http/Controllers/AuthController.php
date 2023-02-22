<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //user register function
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email',
            'password'   => 'required',
        ]);
        //check email id is exists or not
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email Already exists',
                'status'  => 'failed'
            ], 200);
        }
        //create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);
        //generate token
        $token = $user->createToken($request->email)->plainTextToken;
        //send response
        return response([
            'token'   => $token,
            'message' => 'User Register Succesfully',
            'status'  => 'Success'
        ], 201);
    }
    //user login function
    public function login(Request $request)
    {
        //validation
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        //check login details
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;
            return response([
                'token'   => $token,
                'message' => 'User Logged in Succesfully',
                'status'  => 'Success'
            ], 201);
        } else {
            return response([
                'message' => 'failed',
                'status'  => 'Failed'
            ], 401);
        }
    }
    //user logout function
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout',
            'status'  => 'Success'
        ], 200);
    }
}
