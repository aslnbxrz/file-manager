<?php

namespace Aslnbxrz\FileManager\Http\Repository;

use Aslnbxrz\FileManager\DTO\GeneratedPathFileDTO;
use Aslnbxrz\FileManager\DTO\GeneratePathFileDTO;
use Aslnbxrz\FileManager\Helpers\FileManagerHelper;
use Aslnbxrz\FileManager\Models\Files;
use DomainException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Aslnbxrz\FileManager\Http\Repository\Interfaces\FileInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class FileRepository implements FileInterface
{
    public function uploadFile(GeneratePathFileDTO $generatePathFileDTO, $isFront = false)
    {
        DB::beginTransaction();
        try {
            $generatedDTO = $this->generatePath($generatePathFileDTO);
            $generatedDTO->origin_name = $generatePathFileDTO->file->getClientOriginalName();
            $generatedDTO->file_size = $generatePathFileDTO->file->getSize();
            $generatedDTO->folder_id = $generatePathFileDTO->folder_id;
            $generatePathFileDTO->file->move($generatedDTO->file_folder, $generatedDTO->file_name . '.' . $generatedDTO->file_ext);

            $file = $this->createFileModel($generatedDTO, $isFront);

            if (in_array($generatePathFileDTO->file->getClientOriginalExtension(), $file->getIsImage())) {
                $this->createThumbnails($file);
            }

//            if ($isFront) {
//                File::delete($generatedDTO->file_folder . '/' . $generatedDTO->file_name . '.' . $generatedDTO->file_ext);
//            }
//            FileThumbsJob::dispatch($file);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }
        return $file;
    }

    public function generatePath(GeneratePathFileDTO $generatePathFileDTO): GeneratedPathFileDTO
    {
        $created_at = time();
        $file = $generatePathFileDTO->file;
        $originalExtension = $file->getClientOriginalExtension();

        $y = date("Y", $created_at);
        $m = date("m", $created_at);
        $d = date("d", $created_at);
        $h = date("H", $created_at);
        $i = date("i", $created_at);

        $folders = [$y, $m, $d, $h, $i];

        $file_hash = Str::random(32);
        $file_name = Str::slug($file->getClientOriginalName()) . "_" . Str::random(10);

        $basePath = base_path('static');
        if (!File::isDirectory($basePath)) {
            File::makeDirectory($basePath, 0777);
        }
        $folderPath = '';
        foreach ($folders as $folder) {
            $basePath .= '/' . $folder;
            $folderPath .= $folder . '/';
            if (!is_dir($basePath)) {
                mkdir($basePath, 0777);
                chmod($basePath, 0777);
                Storage::makeDirectory('origin/' . $folderPath);
            }
        }
        if (!is_writable($basePath)) {
            throw new DomainException("Path is not writeable");
        }
        $generatedPathFileDTO = new GeneratedPathFileDTO();
        $generatedPathFileDTO->file_folder = $basePath;

        $path = $basePath . '/' . $file_hash . "." . $originalExtension;
        $generatedPathFileDTO->file_name = $file_hash;

        // if save file name as original name
        if ($generatePathFileDTO->useFileName) {
            $path = $basePath . '/' . $file_name . "." . $originalExtension;
            $generatedPathFileDTO->file_name = $file_name;
        }

        $generatedPathFileDTO->file_ext = $originalExtension;
        $generatedPathFileDTO->file_path = $path;
        $generatedPathFileDTO->created_at = $created_at;
        $generatedPathFileDTO->folder_path = $folderPath;

        return $generatedPathFileDTO;
    }

    private function createFileModel(GeneratedPathFileDTO $generatedDTO, $isFront)
    {
        $data = [
            'title' => $generatedDTO->origin_name,
            'description' => $generatedDTO->origin_name,
            'slug' => $generatedDTO->file_name,
            'ext' => $generatedDTO->file_ext,
            'file' => $generatedDTO->file_name . '.' . $generatedDTO->file_ext,
            'folder' => $generatedDTO->folder_path,
            'folder_id' => $generatedDTO->folder_id,
            'domain' => config('system.STATIC_URL'),
            'user_id' => Auth::id(),
            'path' => $generatedDTO->file_folder,
            'size' => $generatedDTO->file_size,
            'is_front' => $isFront ? 1 : 0
        ];
        try {
            $file = Files::query()->create($data);
        } catch (\Exception $exception) {
            throw new DomainException($exception->getMessage(), $exception->getCode());
        }
        return $file;
    }

    private function createThumbnails(Files $file): void
    {
        $thumbsImages = FileManagerHelper::getThumbsImage();
        $origin = $file->getDist();
        try {
            foreach ($thumbsImages as $thumbsImage) {
                $width = $thumbsImage['w'];
                $quality = $thumbsImage['q'];
                $slug = $thumbsImage['slug'];
                $newFileDist = $file->path . '/' . $file->slug . "_" . $slug . "." . $file->ext;
                if ($file->ext == 'svg') {
                    copy($origin, $newFileDist);
                } else {
                    $img = Image::make($origin);
                    $height = $width / ($img->getWidth() / $img->getHeight());
                    $img->resize($width, $height)->save($newFileDist, $quality);
                }
            }
        } catch (Throwable $e) {
            report($e);
            return;
        }
//        $folder = Storage::disk('local')->path('origin');
//        rename($origin, $folder . '/' . $file->folder . $file->file);
    }

    public function downloadFile(Files $file, $type): BinaryFileResponse
    {
        $folder = Storage::disk('local')->path('origin');
        $link = $folder . '/' . $file->folder . $file->slug . '_' . $type . '.' . $file->ext;
        $headers = ['Content-Type' => 'application/' . $file->ext,];
        return response()->download($link, $file->title . '.' . $file->ext, $headers);
    }

    public function checkAndUpload(Request $request, UploadedFile $file, $isFront = false)
    {
        if (!in_array($ext = $file->extension(), explode(',', $isFront ? Files::AVAILABLE_EXTENSIONS : 'jpeg,jpg,png,mp4'))) {
            throw new AccessDeniedException("Unknown extension $ext", 422);
        }

        $generatePathFileDTO = new GeneratePathFileDTO();
        $generatePathFileDTO->folder_id = $request->get('folder_id');
        $generatePathFileDTO->file = $file;
        return $this->uploadFile($generatePathFileDTO, $isFront);
    }
}
