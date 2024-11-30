<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Str;

class S3Service
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

    public function uploadImg($folder, $image)
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $folder . '/' . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . "-" . Str::random(16) . "." . $image->getClientOriginalExtension(),
                'Body' => $image->get(),
                'ContentType' => $image->getMimeType()
            ]);

            return $result['ObjectURL'];
        } catch (AwsException $error) {
            throw new \Exception('Error uploading ' . Str::beforeLast($folder, 's') . ' image');
        }
    }

    public function deleteImg($folder, $file)
    {
        try {
            return $this->s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $folder . '/' . basename($file),
            ]);
        } catch (AwsException $error) {
            throw new \Exception('Error deleting ' . Str::beforeLast($folder, 's') . ' image');
        }
    }
}
