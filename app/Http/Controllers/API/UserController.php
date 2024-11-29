<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use App\Models\User;
use Aws\Exception\AwsException;
use Illuminate\Support\Str;

class UserController extends Controller
{

    protected $s3;

    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'required|string|unique:users,nomor_telepon,' . $request->user()->id,
            'photo' => 'nullable|image|max:15000',
        ]);

        $request->user()->update([
            'fullname' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->phone
        ]);

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');

            try {
                $result = $this->s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'users/' . pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(16) . '.' . $photo->getClientOriginalExtension(),
                    'Body' => $photo->get(),
                    'ContentType' => $photo->getMimeType(),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error uploading photo',
                ], 500);
            }

            $request->user()->update([
                'photo' => $result['ObjectURL']
            ]);

        }

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
