<?php

namespace JaviManga\CreateCrud;

use JaviManga\CreateCrud\Console\Commands\CreateCRUD;
use JaviManga\InitDB\Console\Commands\CloneDB;
use JaviManga\InitDB\Console\Commands\CreateDB;
use JaviManga\InitDB\Console\Commands\DropDB;
use Illuminate\Support\ServiceProvider;

class CreateCRUDServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                createCRUD::class,
            ]);
        }
    }
}
