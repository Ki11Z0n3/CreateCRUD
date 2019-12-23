<?php

namespace JaviManga\CreateCrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;
use PDOException;

class CreateCRUD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:crud {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear CRUD de tabla';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            if ($this->argument('table')) {
                $table = $this->argument('table');
                $database = env('DB_DATABASE');
                if(Schema::hasTable($table)){
                    echo 'ok';
                }else{
                    $this->error('Error: la tabla ' . $table . ' no existe');
                }
            } else {
                $this->error('Error: no se ha escrito la tabla');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
