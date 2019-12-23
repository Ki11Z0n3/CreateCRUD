<?php

namespace JaviManga\InitDB;

use JaviManga\InitDB\Console\Commands\CloneDB;
use JaviManga\InitDB\Console\Commands\CreateDB;
use JaviManga\InitDB\Console\Commands\DropDB;
use JaviManga\InitDB\Console\Commands\createCRUD;
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
