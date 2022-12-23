<?php

use App\Models\User;
use Illuminate\Http\Request;
use Aslnbxrz\FileManager\Http\Controllers\FileManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*--------------------------------------------------------------------------------
            File manager Controller  => START
--------------------------------------------------------------------------------*/
Route::prefix('v1')->group(function () {
//    Route::middleware(['auth', 'scope:' . implode(',', User::ALL_ROLES)])->group(function () {
//        Route::prefix('admin/file-manager/folder')->group(function () {
//            Route::get('/', [FileManagerFolderController::class,"index"]);
//            Route::get('/{id}', '\Modules\FileManager\Http\Controllers\FilemanagerFolderController@show');
//            Route::post('/', '\Modules\FileManager\Http\Controllers\FilemanagerFolderController@create');
//            Route::put('/{id}', '\Modules\FileManager\Http\Controllers\FilemanagerFolderController@update');
//            Route::delete('/{id}', '\Modules\FileManager\Http\Controllers\FilemanagerFolderController@delete');
//        });
        Route::prefix('admin/file-manager')->group(function () {
            Route::get('/', [FileManagerController::class, "index"]);
            Route::get('{file}', [FileManagerController::class, "show"]);
            Route::put('{file}', [FileManagerController::class, "update"]);
            Route::delete('{file}', [FileManagerController::class, "delete"]);
            Route::post('upload', [FileManagerController::class, "upload"]);
        });
//    });

    Route::prefix('file-manager')->group(function () {
        Route::delete('{file}', [FileManagerController::class, "delete"]);
        Route::post('upload', [FileManagerController::class, "frontUpload"]);
    });
});
/*--------------------------------------------------------------------------------
            File manager Controller => END
--------------------------------------------------------------------------------*/
