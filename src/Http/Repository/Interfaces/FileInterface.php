<?php

namespace Aslnbxrz\FileManager\Http\Repository\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\Filemanager\Dto\GeneratePathFileDTO;
use Modules\Filemanager\Entities\Files;

interface FileInterface
{
    public function generatePath(GeneratePathFileDTO $generatePathFileDTO);

    public function uploadFile(GeneratePathFileDTO $generatePathFileDTO);

    public function downloadFile(Files $file, $type);

    public function checkAndUpload(Request $request, UploadedFile $file, $isFront = false);
}
