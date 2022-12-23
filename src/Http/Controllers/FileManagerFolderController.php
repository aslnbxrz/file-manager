<?php

namespace Aslnbxrz\FileManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Aslnbxrz\FileManager\Models\Folder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class FileManagerFolderController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Folder::class);
        if (!empty($title = $request->get('title'))) {
            $query->where('title', 'ILIKE', '%' . $title . '%');
        }
        $query->allowedFilters($this->filterKey($request));
        $query->allowedIncludes($this->getIncludes($request));
        $query->allowedSorts($request->get('sort'));
        return $query->paginate($request->get('per_page'));
    }

    public function create(Request $request)
    {
        $request->validate(Folder::rules());
        return Folder::query()->create($request->all());
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate(['title' => 'string']);
        $folder->update(['title' => $request->get("title")]);
        return $folder;
    }

    public function delete(Folder $folder)
    {
        $deletedFolder = clone $folder;
        $folder->delete();
        return successResponse("Deleted", $deletedFolder);
    }
}
