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
        $remember = $request->input('remember_me', false);

        try {
            $user = User::where('email', $credentials['email'])
                ->where('is_admin', false)
                ->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response([
                    'message' => 'Wrong email or password',
                ], 401);
            }

            if ($remember) {
                JWTAuth::factory()->setTTL(43800);
            }

            $token = JWTAuth::fromUser($user);
            $ttl = $remember ? 43800 : config('jwt.ttl');
            $ttl *= 60;

            return response([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $ttl,
                'remember_me' => $remember
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
        $remember = $request->input('remember_me', false);

        try {
            $admin = User::where('email', $credentials['email'])
                ->where('is_admin', true)
                ->first();

            if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
                return response([
                    'message' => 'Wrong email or password',
                ], 401);
            }

            // Set custom TTL if remember_me is true
            if ($remember) {
                $customTTL = 60 * 24 * 30; // 30 days in minutes
                JWTAuth::factory()->setTTL($customTTL);
            }

            $token = JWTAuth::fromUser($admin);
            
            // Get the actual TTL that was used
            $ttl = $remember ? 60 * 24 * 30 : config('jwt.ttl');

            return response([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $ttl * 60,
                'remember_me' => $remember
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