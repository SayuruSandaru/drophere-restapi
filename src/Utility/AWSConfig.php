<?php

namespace App\Utility;

use Aws\S3\S3Client;

class AWSConfig
{
    public static function getS3Client()
    {
        // $bucket = 'drophere-bucket';
        // $region = 'us-east-1';
        // $accessKey = getenv('AWS_ACCESS_KEY_ID');
        // $secretKey = getenv('AWS_SECRET_ACCESS_KEY');
        $bucket = 'drophere-bucket';
        $region = 'us-east-1';
        $accessKey = 'AKIA4LD2C6DFPMNNB3C6';
        $secretKey = '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';

        return new S3Client([
            'region'  => $region,
            'version' => 'latest',
            'credentials' => [
                'key'    => $accessKey,
                'secret' => $secretKey,
            ]
        ]);
    }
}
