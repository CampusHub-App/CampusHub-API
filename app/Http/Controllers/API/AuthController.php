<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

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

        $user = User::create([
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

        $user = User::where('email', $request->email)->first();

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {

            $token = $user->createToken('auth_token')->plainTextToken;

            return response([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'is_admin' => $user->is_admin,
            ]);

        } else {

            return response([
                'message' => 'Wrong email or password',
            ], 401);

        }
    }

    public function logout(Request $request)
    {

        $request->user()->remember_token = null;
        $request->user()->save();
        $request->user()->tokens()->delete();
        redirect('/');
        return response([
            'message' => 'Logged out successfully',
        ]);

    }
}