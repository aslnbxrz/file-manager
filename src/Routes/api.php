<?php

use App\Models\User;
use Aslnbxrz\FileManager\Http\Controllers\FileManagerController;
use Aslnbxrz\FileManager\Http\Controllers\FileManagerFolderController;

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
    Route::middleware(['auth', 'scope:' . implode(',', User::ADMIN_ROLES)])->group(function () {
        Route::prefix('admin/file-manager/folder')
            ->controller(FileManagerFolderController::class)
            ->group(function () {
                Route::get('/', "index");
                Route::get('/{folder}', "show");
                Route::post('/', "create");
                Route::put('/{folder}', "update");
                Route::delete('/{folder}', "delete");
            });
        Route::prefix('admin/file-manager')
            ->controller(FileManagerController::class)
            ->group(function () {
                Route::get('/', "index");
                Route::get('{file}', "show");
                Route::put('{file}', "update");
                Route::delete('{file}', "delete");
                Route::post('upload', "upload");
            });
    });

    Route::prefix('file-manager')
        ->controller(FileManagerController::class)
        ->group(function () {
            Route::delete('{file}', "delete");
            Route::post('upload', "frontUpload");
        });
});
/*--------------------------------------------------------------------------------
            File manager Controller => END
--------------------------------------------------------------------------------*/
