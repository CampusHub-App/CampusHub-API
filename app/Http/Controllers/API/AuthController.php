<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'phone' => 'required|string|unique:users,nomor_telepon',
        ]);

        User::create([
            'fullname' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nomor_telepon' => $request->phone,
        ]);

        return response([
            'message' => 'User created successfully',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            $user = User::where('email', $credentials['email'])
                ->where('is_admin', false)
                ->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response([
                    'message' => 'Wrong email or password',
                ], 401);
            }

            $token = JWTAuth::fromUser($user);

            return response([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60
            ]);
        } catch (JWTException $e) {
            return response([
                'message' => 'Could not create token',
            ], 500);
        }
    }

    public function atmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            $admin = User::where('email', $credentials['email'])
                ->where('is_admin', true)
                ->first();

            if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
                return response([
                    'message' => 'Wrong email or password',
                ], 401);
            }

            $token = JWTAuth::fromUser($admin);

            return response([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60
            ]);
        } catch (JWTException $e) {
            return response([
                'message' => 'Could not create token',
            ], 500);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response([
                'message' => 'Logged out successfully',
            ]);
        } catch (JWTException $e) {
            return response([
                'message' => 'Failed to logout, please try again',
            ], 500);
        }
    }

}