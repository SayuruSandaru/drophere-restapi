<?php

namespace App\Service;

use App\Repository\FileUploadRepository;

class FileUploadService
{
    private $fileUploadRepository;

    public function __construct(FileUploadRepository $fileUploadRepository)
    {
        $this->fileUploadRepository = $fileUploadRepository;
    }

    public function uploadFile($file)
    {
        return $this->fileUploadRepository->uploadFile($file);
    }
}
