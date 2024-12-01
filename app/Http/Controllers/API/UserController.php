<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\S3Service;

class UserController extends Controller
{

    protected $s3;

    public function __construct()
    {
        $this->s3 = new S3Service();
    }

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

                try {
                    $this->s3->deleteImg('users', $request->user()->photo);
                } catch (\Exception $error) {
                    return response([
                        'message' => $error->getMessage(),
                    ], 500);
                }

            }

            try {
                $photo = $this->s3->uploadImg('users', $request->file('photo'));
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }

            $request->user()->update([
                'photo' => $photo
            ]);
        }

        $request->user()->update([
            'fullname' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->phone
        ]);

        return response([
            'message' => 'Profile updated successfully',
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
            ['remember_token' => null]
        );

        $request->user()->tokens()->delete();
        redirect('/');

        return response([
            'message' => 'Password updated successfully, please relogin',
        ]);
    }

    public function delete(Request $request)
    {
        $request->user()->tokens()->delete();
        $request->user()->delete();
        redirect('/');
        return response([
            'message' => 'User deleted successfully',
        ]);
    }

}
