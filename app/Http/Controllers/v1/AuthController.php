<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * API of User registration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $token
     */
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'first_name' => 'required',
            'email'      => 'required|email',
            'password'   => 'required',
        ]);
        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email Already exists',
                'status'  => 'failed'
            ], 200);
        }
        // dd($request->all());
        $request['password'] = Hash::make($request->password);
        $user = User::create($request->only('first_name', 'email', 'password'));
        // $user = User::create([
        //     'first_name' => $request->first_name,
        //     'email'      => $request->email,
        //     'password'   => Hash::make($request->password),
        // ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'token'   => $token,
            'message' => 'User Register Succesfully',
            'status'  => 'Success'
        ], 201);
    }
    /**
     * API of User login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $token
     */
    public function login(Request $request)
    {
        //validation
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
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
    /**
     * API of User Logout
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout',
            'status'  => 'Success'
        ], 200);
    }
}
