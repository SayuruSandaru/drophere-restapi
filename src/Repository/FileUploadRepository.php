<?php

namespace App\Repository;

require 'vendor/autoload.php';

use App\Utility\AWSConfig;
use Aws\Exception\AwsException;

class FileUploadRepository
{
    public function uploadFile($file)
    {
        try {
            $s3 = AWSConfig::getS3Client();
            $bucket = 'drophere-bucket';

            $filePath = $file['tmp_name'];
            $fileName = basename($file['name']);
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $fileName,
                'SourceFile' => $filePath,
                // 'ACL'    => 'public-read'
            ]);

            return $result['ObjectURL'];
        } catch (AwsException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
