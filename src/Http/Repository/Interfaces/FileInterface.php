<?php

namespace Aslnbxrz\FileManager\Http\Repository\Interfaces;

use Aslnbxrz\FileManager\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Aslnbxrz\FileManager\DTO\GeneratePathFileDTO;

interface FileInterface
{
    public function generatePath(GeneratePathFileDTO $generatePathFileDTO);

    public function uploadFile(GeneratePathFileDTO $generatePathFileDTO);

    public function downloadFile(Files $file, $type);

    public function checkAndUpload(Request $request, UploadedFile $file, $isFront = false);
}
