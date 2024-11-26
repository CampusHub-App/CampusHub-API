<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class UserController extends Controller
{
    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,nomor_telepon',
            'photo' => 'image|max:15000',
        ]);
        
        $photo = $request->file('photo');
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        
        try {
            $result = $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => 'users/' . $photo->getClientOriginalName(),
                'Body'   => $photo->get(),
                'ContentType' => $photo->getMimeType(),
            ]);
        } catch (AwsException $error) {
            return response([
                'message' => 'Error uploading photo',
                'error' => $error->getMessage()
            ]);
        }

        $request->user()->fullname = $request->name;
        $request->user()->email = $request->email;
        $request->user()->nomor_telepon = $request->phone;
        $request->user()->photo = $result['ObjectURL'];
        $request->user()->save();

        return response([
            'message' => 'Profile updated successfully',
        ]);
        
    }

    public function read(Request $request)
    {
        return $request->user();
    }
}
