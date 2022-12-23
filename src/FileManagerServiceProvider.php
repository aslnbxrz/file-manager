<?php

namespace Aslnbxrz\FileManager;

use Aslnbxrz\FileManager\Http\Repository\FileRepository;
use Aslnbxrz\FileManager\Http\Repository\Interfaces\FileInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();
    }

    public function register()
    {
        $this->app->bind(FileInterface::class, FileRepository::class);
        $this->app->register(RouteServiceProvider::class);
    }

    protected function offerPublishing()
    {
        if (!function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__ . '/Database/Migrations/create_folders_table.php.stub' => $this->getMigrationFileName('2022_12_23_010706_create_folders_table.php'),
            __DIR__ . '/Database/Migrations/create_files_table.php.stub' => $this->getMigrationFileName('2022_12_23_010707_create_files_table.php'),

        ], 'migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param $migrationFileName
     * @return string
     *
     * @throws BindingResolutionException
     */
    protected function getMigrationFileName($migrationFileName): string
    {

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push($this->app->databasePath() . "/migrations/{$migrationFileName}")
            ->first();
    }
}
