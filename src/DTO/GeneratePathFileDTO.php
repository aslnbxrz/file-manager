<?php
namespace Aslnbxrz\FileManager\DTO;

use Illuminate\Http\UploadedFile;

class GeneratePathFileDTO
{
    public UploadedFile $file;
    public ?int $folder_id;
    public bool $useFileName = false; // save file name as hash or original name
}
