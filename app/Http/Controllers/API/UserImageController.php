<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class UserImageController extends Controller
{
    public function upload(Request $request)
    {

        $request->validate([
            'image' => 'required|image|max:15000',
        ]);
        
        $image = $request->file('image');
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        
        try {
            $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => 'users/' . $image->getClientOriginalName(),
                'Body'   => fopen($image, 'r'),
                'ACL'    => 'public-read'
            ]);
            $request->user()->update([
                'image' => $image->getClientOriginalName(),
            ]);
            $request->user()->save();
            return response("Image uploaded successfully");
        } catch (AwsException $error) {
            return response([
                'message' => 'Error uploading image',
                'error' => $error->getMessage()
            ]);
        }
        
    }
}
