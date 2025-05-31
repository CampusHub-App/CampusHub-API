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
            'message' => 'Pengguna berhasil dibuat',
        ]);
    }

    public function userlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember_me', false);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response([
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $user = JWTAuth::user();
            if ($user->is_admin) {
                return response([
                    'message' => 'Anda tidak boleh mengakses halaman ini.',
                ], 403);
            }

            $ttl = $remember ? 43800 : config('jwt.ttl');
            JWTAuth::factory()->setTTL($ttl);
            $token = JWTAuth::fromUser($user);

            return response([
                'message' => 'Login berhasil',
                'access_token' => $token,
            ]);
        } catch (JWTException $e) {
            return response([
                'message' => 'Gangguan pada server, silahkan coba lagi nanti.',
            ], 500);
        }
    }

    public function adminlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember_me', false);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response([
                    'message' => 'Email atau password salah',
                ], 401);
            }

            $admin = JWTAuth::user();
            if (!$admin->is_admin) {
                return response([
                    'message' => 'Anda tidak boleh mengakses halaman ini.',
                ], 403);
            }

            $ttl = $remember ? 43800 : config('jwt.ttl');
            JWTAuth::factory()->setTTL($ttl);
            $token = JWTAuth::fromUser($admin);

            return response([
                'message' => 'Login berhasil',
                'access_token' => $token,
            ]);
        } catch (JWTException $e) {
            return response([
                'message' => 'Gangguan pada server, silahkan coba lagi nanti.',
            ], 500);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response([
                'message' => 'Berhasil Logout',
            ]);
        } catch (JWTException $e) {
            return response([
                'message' => 'Gagal logout, silahkan cek koneksi internet anda atau hapus cache browser anda.',
            ], 500);
        }
    }

}