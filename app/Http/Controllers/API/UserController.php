<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'required|max:13|string|unique:users,nomor_telepon,' . $request->user()->id,
            'photo' => 'nullable|image|max:15000',
        ]);

        if ($request->hasFile('photo')) {
            if ($request->user()->photo) {
                if (Storage::disk('public')->exists($request->user()->photo)) {
                    Storage::disk('public')->delete($request->user()->photo);
                }
            }

            try {
                $photoFile = $request->file('photo');
                $photoName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(16) . '.' . $photoFile->getClientOriginalExtension();
                $photoPath = $photoFile->storeAs('users', $photoName, 'public');

                User::where('id', $request->user()->id)->update([
                    'photo' => $photoPath,
                ]);
            } catch (\Exception $error) {
                return response([
                    'message' => 'Gagal mengunggah foto: ' . $error->getMessage(),
                ], 500);
            }
        }

        User::where('id', $request->user()->id)->update([
            'fullname' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->phone,
        ]);

        return response([
            'message' => 'Profil berhasil diperbarui',
        ]);
    }

    public function read(Request $request)
    {
        return $request->user()->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'password', 'remember_token']);
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'confirmation' => 'required|string|same:password',
        ]);

        $request->user()->update(
            ['password' => bcrypt($request->password)],
        );

        try {
            JWTAuth::parseToken()->invalidate();
        } catch (JWTException $e) {
            return response([
                'message' => 'Terjadi kesalahan pada server, silahkan coba lagi nanti.',
            ], 500);
        }
        
        return response([
            'message' => 'Password berhasil diperbarui',
        ]);
    }

    public function delete(Request $request)
    {
        if ($request->user()->photo) {
            if (Storage::disk('public')->exists($request->user()->photo)) {
                Storage::disk('public')->delete($request->user()->photo);
            }
        }

        try {
            JWTAuth::parseToken()->invalidate();
        } catch (JWTException $e) {
            return response([
                'message' => 'Terjadi kesalahan pada server, silahkan coba lagi nanti.',
            ], 500);
        }
        
        $request->user()->delete();
        
        return response([
            'message' => 'Akun berhasil dihapus',
        ]);
    }

}
