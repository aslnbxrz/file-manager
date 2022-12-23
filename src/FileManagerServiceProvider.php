<?php

namespace Aslnbxrz\FileManager;

use Aslnbxrz\FileManager\Http\Repository\FileRepository;
use Aslnbxrz\FileManager\Http\Repository\Interfaces\FileInterface;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
//        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
    }

    public function register()
    {
        $this->app->bind(FileInterface::class, FileRepository::class);
        $this->app->register(RouteServiceProvider::class);
    }
}
