<?php

namespace Aslnbxrz\FileManager\Http\Controllers;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Aslnbxrz\Filemanager\Models\Files;
use Aslnbxrz\Filemanager\Helpers\FileManagerHelper;
use Aslnbxrz\FileManager\Http\Repository\Interfaces\FileInterface;
use Spatie\QueryBuilder\QueryBuilder;

class FileManagerController extends Controller
{
    private FileInterface $fileRepository;

    public function __construct(FileInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function index(Request $request)
    {
        $query = QueryBuilder::for(Files::class);
        if (!empty($title = $request->get('title'))) {
            $query->where('title', 'ILIKE', '%' . $title . '%');
        }
        $query->allowedFilters($this->filterKey($request));
        $query->allowedIncludes($this->getIncludes($request));
        $query->allowedSorts($request->get('sort'));
        return $query->paginate($request->get('per_page'));
    }

    public function upload(Request $request, $isFront = false)
    {
        $request->validate(["files" => "required"]);
        $files = $request->file('files');

        $data = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                $data[] = $this->fileRepository->checkAndUpload($request, $file, $isFront);
            }
        } else {
            $data = $this->fileRepository->checkAndUpload($request, $files, $isFront);
        }
        return successResponse("Uploaded successfully", $data);
    }

    public function frontUpload(Request $request)
    {
        return $this->upload($request, true);
    }

    public function update(Request $request, Files $file)
    {
        $request->validate(['title' => 'string|required']);
        $file->update(['title' => $request->get('title')]);
        return $file;
    }

    public function delete(Files $file)
    {
        $oldFile = clone $file;
        $filePath[] = $file->path . "/" . $file->file;
        foreach (FileManagerHelper::getThumbsImage() as $thumbsImage) {
            $filePath[] = $file->path . "/" . $file->slug . "_" . $thumbsImage['slug'] . "." . $file->ext;
        }
        File::delete($filePath);
        $file->delete();
        return successResponse('deleted', $oldFile);
    }
}
