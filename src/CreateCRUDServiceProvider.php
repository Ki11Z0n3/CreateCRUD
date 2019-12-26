<?php

/**
 * @package    javimanga/createcrud
 * @author     Javier Manga <javimanga93@gmail.com>
 * @copyright  2019-2019 The FreakSystem Group
 * @license    https://packagist.org/packages/javimanga/createcrud MIT
 * @link       https://packagist.org/packages/javimanga/createcrud
 * @link       https://github.com/Ki11Z0n3/CreateCRUD
 */

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
