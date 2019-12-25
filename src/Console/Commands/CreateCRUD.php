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
    public $plural = array(
        '/(quiz)$/i' => "$1zes",
        '/^(ox)$/i' => "$1en",
        '/([m|l])ouse$/i' => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i' => "$1es",
        '/([^aeiouy]|qu)y$/i' => "$1ies",
        '/(hive)$/i' => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i' => "ses",
        '/([ti])um$/i' => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
        '/(bu)s$/i' => "$1ses",
        '/(alias)$/i' => "$1es",
        '/(octop)us$/i' => "$1i",
        '/(ax|test)is$/i' => "$1es",
        '/(us)$/i' => "$1es",
        '/s$/i' => "s",
        '/$/' => "s"
    );

    public $singular = array(
        '/(quiz)zes$/i' => "$1",
        '/(matr)ices$/i' => "$1ix",
        '/(vert|ind)ices$/i' => "$1ex",
        '/^(ox)en$/i' => "$1",
        '/(alias)es$/i' => "$1",
        '/(octop|vir)i$/i' => "$1us",
        '/(cris|ax|test)es$/i' => "$1is",
        '/(shoe)s$/i' => "$1",
        '/(o)es$/i' => "$1",
        '/(bus)es$/i' => "$1",
        '/([m|l])ice$/i' => "$1ouse",
        '/(x|ch|ss|sh)es$/i' => "$1",
        '/(m)ovies$/i' => "$1ovie",
        '/(s)eries$/i' => "$1eries",
        '/([^aeiouy]|qu)ies$/i' => "$1y",
        '/([lr])ves$/i' => "$1f",
        '/(tive)s$/i' => "$1",
        '/(hive)s$/i' => "$1",
        '/(li|wi|kni)ves$/i' => "$1fe",
        '/(shea|loa|lea|thie)ves$/i' => "$1f",
        '/(^analy)ses$/i' => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
        '/([ti])a$/i' => "$1um",
        '/(n)ews$/i' => "$1ews",
        '/(h|bl)ouses$/i' => "$1ouse",
        '/(corpse)s$/i' => "$1",
        '/(us)es$/i' => "$1",
        '/s$/i' => ""
    );

    public $irregular = array(
        'move' => 'moves',
        'foot' => 'feet',
        'goose' => 'geese',
        'sex' => 'sexes',
        'child' => 'children',
        'man' => 'men',
        'tooth' => 'teeth',
        'person' => 'people'
    );

    public $uncountable = array(
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    );
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
                $camelCaseTable = ucwords($table, '_');
                $camelCaseTable = str_ireplace('_', '', $camelCaseTable);
                $camelCaseTable = $this->singularize($camelCaseTable);
                $database = env('DB_DATABASE');
                if (Schema::hasTable($table)) {
                    $appImport = [];
                    $appUse = [];
                    $appComponent = [];
                    if (file_exists("app/{$camelCaseTable}.php")) {
                        $copy = $this->anticipate("Ya existe el modelo {$camelCaseTable}.php, ¿desea sobreescribirlo? Si (recomendado) / No", ['si', 'no'], 'si');
                        if (mb_strtolower($copy) == 'si') {
                            exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/Model.php > app/{$camelCaseTable}.php");
                        }
                    } else {
                        exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/Model.php > app/{$camelCaseTable}.php");
                    }
                    if (file_exists("app/Http/Controllers/{$camelCaseTable}Controller.php")) {
                        $copy = $this->anticipate("Ya existe el controlador {$camelCaseTable}Controller.php, ¿desea sobreescribirlo? Si (recomendado) / No", ['si', 'no'], 'si');
                        if (mb_strtolower($copy) == 'si') {
                            exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/ModelController.php > app/Http/Controllers/{$camelCaseTable}Controller.php");
                        }
                    } else {
                        exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/ModelController.php > app/Http/Controllers/{$camelCaseTable}Controller.php");
                    }
                    if (!file_exists("resources/js/components/default")) {
                        mkdir("resources/js/components/default", 0755, true);
                    }
                    if (!file_exists("resources/js/components/default/TableComponent.vue")) {
                        exec("cp vendor/javimanga/createcrud/src/TableComponent.vue resources/js/components/default/TableComponent.vue");
                    }
                    if (!file_exists("resources/js/components/default/ModalComponent.vue")) {
                        exec("cp vendor/javimanga/createcrud/src/ModalComponent.vue resources/js/components/default/ModalComponent.vue");
                    }
                    if (!file_exists("resources/js/helpers.js")) {
                        exec("cp vendor/javimanga/createcrud/src/helpers.js resources/js/helpers.js");
                    }
                    if (!file_exists("resources/views/{$camelCaseTable}")) {
                        mkdir("resources/views/{$camelCaseTable}", 0755, true);
                    }
                    exec("cp vendor/javimanga/createcrud/src/template.blade.php resources/views/{$camelCaseTable}/template.blade.php");
                    $contents = file_get_contents('routes/web.php');
                    $pattern = preg_quote("Route::resource('{$camelCaseTable}', '{$camelCaseTable}Controller');", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        exec("echo Route::resource('{$camelCaseTable}', '{$camelCaseTable}Controller'); >> routes/web.php");
                    }
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("import VueSweetalert2 from 'vue-sweetalert2';", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        exec('npm install -save vue-sweetalert2');
                        $appImport[] = "import VueSweetalert2 from 'vue-sweetalert2';";
                        $appImport[] = "import 'sweetalert2/dist/sweetalert2.min.css';";
                        $appUse[] = "Vue.use(VueSweetalert2);";
                    }
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("Vue.component('table-component', require('./components/default/TableComponent.vue').default);", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $appComponent[] = "Vue.component('table-component', require('./components/default/TableComponent.vue').default);";
                    }
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("Vue.component('modal-component', require('./components/default/ModalComponent.vue').default);", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $appComponent[] = "Vue.component('modal-component', require('./components/default/ModalComponent.vue').default);";
                    }
                    if (count($appImport) != 0 || count($appUse) != 0 || count($appComponent) != 0) {
                        $this->info('Añada las siguientes lineas a resources/js/app.js');
                        if (count($appImport) != 0) {
                            foreach ($appImport as $m) {
                                $this->info($m);
                            }
                        }
                        if (count($appUse) != 0) {
                            foreach ($appUse as $m) {
                                $this->info($m);
                            }
                        }
                        if (count($appComponent) != 0) {
                            foreach ($appComponent as $m) {
                                $this->info($m);
                            }
                        }
                        $this->info('Después ejecute: npm run dev');
                    } else {
                        exec('npm run dev');
                    }
                    Artisan::call('config:cache');
                    $this->info(url(route($camelCaseTable . '.index')));
                } else {
                    $this->error('Error: la tabla ' . $table . ' no existe');
                }
            } else {
                $this->error('Error: no se ha escrito la tabla');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    public function singularize($string)
    {
        if (in_array(strtolower($string), $this->uncountable))
            return $string;

        foreach ($this->irregular as $result => $pattern) {
            $pattern = '/' . $pattern . '$/i';

            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        foreach ($this->singular as $pattern => $result) {
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }


    }
}
