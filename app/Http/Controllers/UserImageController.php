<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class UserImageController extends Controller
{
    public function mkimage(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:15000',
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
            $result = $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => 'users/' . $image->getClientOriginalName(),
                'Body'   => $image,
            ]);
            echo "File uploaded successfully. URL: " . $result['ObjectURL'] . "\n";
        } catch (AwsException $e) {
            echo "Error uploading file: " . $e->getMessage() . "\n";
        }
        
    }
}
