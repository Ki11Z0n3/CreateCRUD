<?php

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    javimanga/createcrud
 * @author     Javier Manga <javimanga93@gmail.com>
 * @copyright  2019-2019 The FreakSystem Group
 * @license    https://packagist.org/packages/javimanga/createcrud MIT
 * @link       https://packagist.org/packages/javimanga/createcrud
 * @link       https://github.com/Ki11Z0n3/CreateCRUD
 */

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
    protected $signature = 'create:crud {table} {--r}';

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
            //SI RECIBO PARÁMETRO
            if ($this->argument('table')) {

                //NOMBRE DE TABLA PASADO POR PARÁMETRO
                $table = $this->argument('table');

                //NOMBRE DE TABLA A CAMELCASE
                $camelCaseTable = $this->nameToCamelCase(str_ireplace('_', '', ucwords($table, '_')));

                //NOMBRE DE BASE DE DATOS DE ENV
                $database = env('DB_DATABASE');

                //SI EXISTE LA TRABLA
                if (Schema::hasTable($table)) {
                    $appImport = [];
                    $appUse = [];
                    $appComponent = [];

                    //SI NO EXISTE EL MODELO APP/"CAMELCASETABLE".PHP
                    if (file_exists("app/{$camelCaseTable}.php")) {
                        $copy = $this->anticipate("Ya existe el modelo {$camelCaseTable}.php, ¿desea sobreescribirlo? Si (recomendado) / No", ['si', 'no'], 'si');
                        if (mb_strtolower($copy) == 'si') {
                            $this->info('Creando modelo');
                            exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/Model.php > app/{$camelCaseTable}.php");
                        }
                    } else {
                        $this->info('Creando modelo');
                        exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/Model.php > app/{$camelCaseTable}.php");
                    }

                    //SI NO EXISTE EL CONTROLADOR APP/HTTP/CONTROLLERS/"CAMELCASETABLE"CONTROLLER.PHP
                    if (file_exists("app/Http/Controllers/{$camelCaseTable}Controller.php")) {
                        $copy = $this->anticipate("Ya existe el controlador {$camelCaseTable}Controller.php, ¿desea sobreescribirlo? Si (recomendado) / No", ['si', 'no'], 'si');
                        if (mb_strtolower($copy) == 'si') {
                            $this->info('Creando controlador');
                            exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/ModelController.php > app/Http/Controllers/{$camelCaseTable}Controller.php");
                        }
                    } else {
                        $this->info('Creando controlador');
                        exec("sed 's/:Model/{$camelCaseTable}/g' vendor/javimanga/createcrud/src/ModelController.php > app/Http/Controllers/{$camelCaseTable}Controller.php");
                    }

                    //SI NO EXISTE LA CARPETA RESOURCES/VIEWS/"CAMELCASETABLE"
                    if (!file_exists("resources/views/{$camelCaseTable}")) {
                        $this->info("Creando carpeta resources/views/{$camelCaseTable}");
                        mkdir("resources/views/{$camelCaseTable}", 0755, true);
                    }

                    //COPIAMOS LA VISTA BLADE EN RESOURCES/VIEWS/"CAMELCASETABLE"/TABLE.BLADE.PHP
                    $this->info("Creando vista resources/views/{$camelCaseTable}/template.blade.php");
                    exec("cp vendor/javimanga/createcrud/src/template.blade.php resources/views/{$camelCaseTable}/template.blade.php");

                    //SI EXISTE PARÁMETRO MODO REDUCIDO SOLO CREAMOS MODELO Y CONTROLADOR
                    if($this->option('r')){

                        //BUSCAMOS SI EXISTE LA RUTA EN ROUTES/WEB.PHP
                        $contents = file_get_contents('routes/web.php');
                        $pattern = preg_quote("Route::resource('{$camelCaseTable}', '{$camelCaseTable}Controller');", '/');
                        $pattern = "/^.*$pattern.*\$/m";
                        if (!preg_match_all($pattern, $contents, $matches)) {
                            $this->info("Creando ruta");
                            exec("echo Route::resource('{$camelCaseTable}', '{$camelCaseTable}Controller'); >> routes/web.php");
                        }

                        //LIMPIAMOS CACHE DE CONFIGURACIÓN
                        $this->info("Limpiando cache de configuración");
                        Artisan::call('config:cache');

                        //MOSTRAMOS LA URL DEL CRUD EN EL TERMINAL
                        $this->info("Acceda a la siguiente url: " . url(route($camelCaseTable . '.index')));
                        return false;
                    }

                    //SI NO EXISTE LA CARPETA APP/HTTP/TRAITS
                    if (!file_exists("app/Http/Traits")) {
                        $this->info('Creando carpeta app/Http/Traits');
                        mkdir("app/Http/Traits", 0755, true);
                    }

                    //SI NO EXISTE EL MODELO APP/HTTP/TRAITS/FILTERABLE.PHP
                    if (!file_exists("app/Http/Traits/Filterable.php")) {
                        $this->info('Creando modelo app/Http/Traits/Filterable.php');
                        exec("cp vendor/javimanga/createcrud/src/Http/Traits/Filterable.php app/Http/Traits/Filterable.php");
                    }

                    //SI NO EXISTE LA CARPETA RESOURCES/JS/COMPONENTS/DEFAULT
                    if (!file_exists("resources/js/components/default")) {
                        $this->info('Creando carpeta resources/js/components/default');
                        mkdir("resources/js/components/default", 0755, true);
                    }

                    //SI NO EXISTE EL COMPONENTE RESOURCES/JS/COMPONENTS/DEFAULT/TABLECOMPONENT.VUE
                    if (!file_exists("resources/js/components/default/TableComponent.vue")) {
                        $this->info('Creando componente resources/js/components/default/TableComponent.vue');
                        exec("cp vendor/javimanga/createcrud/src/TableComponent.vue resources/js/components/default/TableComponent.vue");
                    }

                    //SI NO EXISTE EL COMPONENTE RESOURCES/JS/COMPONENTS/DEFAULT/MODALCOMPONENT.VUE
                    if (!file_exists("resources/js/components/default/ModalComponent.vue")) {
                        $this->info('Creando componente resources/js/components/default/ModalComponent.vue');
                        exec("cp vendor/javimanga/createcrud/src/ModalComponent.vue resources/js/components/default/ModalComponent.vue");
                    }

                    //SI NO EXISTE EL COMPONENTE RESOURCES/JS/COMPONENTS/DEFAULT/SELECTFILTERCOMPONENT.VUE
                    if (!file_exists("resources/js/components/default/SelectFilterComponent.vue")) {
                        $this->info('Creando componente resources/js/components/default/SelectFilterComponent.vue');
                        exec("cp vendor/javimanga/createcrud/src/SelectFilterComponent.vue resources/js/components/default/SelectFilterComponent.vue");
                    }

                    //SI NO EXISTE EL SCRIPT RESOURCES/JS/HELPERS.JS
                    if (!file_exists("resources/js/helpers.js")) {
                        $this->info('Creando script resources/js/helpers.js');
                        exec("cp vendor/javimanga/createcrud/src/helpers.js resources/js/helpers.js");
                    }

                    //BUSCAMOS SI EXISTE LA RUTA EN ROUTES/WEB.PHP
                    $contents = file_get_contents('routes/web.php');
                    $pattern = preg_quote("Route::resource('{$camelCaseTable}', '{$camelCaseTable}Controller');", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $this->info("Creando ruta");
                        exec("echo Route::resource('{$camelCaseTable}', '{$camelCaseTable}Controller'); >> routes/web.php");
                    }

                    //BUSCAMOS SI EXISTE EL COMPONENTE VUESWEETALERT2 EN RESOURCES/JS/APP.JS
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("import VueSweetalert2 from 'vue-sweetalert2';", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $this->info("Instalando vue-sweetalert2");
                        exec('npm install -save vue-sweetalert2');
                        $appImport[] = "import VueSweetalert2 from 'vue-sweetalert2';";
                        $appImport[] = "import 'sweetalert2/dist/sweetalert2.min.css';";
                        $appUse[] = "Vue.use(VueSweetalert2);";
                    }

                    //BUSCAMOS SI EXISTE EL COMPONENTE V-SELECT EN RESOURCES/JS/APP.JS
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("import VueSweetalert2 from 'vue-sweetalert2';", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $this->info("Instalando vue-select");
                        exec('npm install -save vue-select');
                        $appImport[] = "import vSelect from 'vue-select';";
                        $appImport[] = "import 'vue-select/dist/vue-select.css';";
                        $appComponent[] = "Vue.component('v-select', vSelect);";
                    }

                    //BUSCAMOS SI EXISTE EL COMPONENTE TABLECOMPONENT EN RESOURCES/JS/APP.JS
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("Vue.component('table-component', require('./components/default/TableComponent.vue').default);", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $appComponent[] = "Vue.component('table-component', require('./components/default/TableComponent.vue').default);";
                    }

                    //BUSCAMOS SI EXISTE EL COMPONENTE MODALCOMPONENT EN RESOURCES/JS/APP.JS
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("Vue.component('modal-component', require('./components/default/ModalComponent.vue').default);", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $appComponent[] = "Vue.component('modal-component', require('./components/default/ModalComponent.vue').default);";
                    }

                    //BUSCAMOS SI EXISTE EL COMPONENTE SELECTFILTERCOMPONENT EN RESOURCES/JS/APP.JS
                    $contents = file_get_contents('resources/js/app.js');
                    $pattern = preg_quote("Vue.component('select-filter-component', require('./components/default/SelectFilterComponent.vue').default);", '/');
                    $pattern = "/^.*$pattern.*\$/m";
                    if (!preg_match_all($pattern, $contents, $matches)) {
                        $appComponent[] = "Vue.component('select-filter-component', require('./components/default/SelectFilterComponent.vue').default);";
                    }

                    //COMPROBAMOS SI HAY QUE AÑADIR LINEAS EN RESOURCES/JS/APP.JS
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

                        //SI NO HAY QUE AÑADIR LINEAS A RESOURCES/JS/APP.JS SE EJECUTA NPM RUN DEV
                        $this->info("Ejecutando npm run dev");
                        exec('npm run dev');
                    }

                    //LIMPIAMOS CACHE DE CONFIGURACIÓN
                    $this->info("Limpiando cache de configuración");
                    Artisan::call('config:cache');

                    //MOSTRAMOS LA URL DEL CRUD EN EL TERMINAL
                    $this->info("Acceda a la siguiente url: " . url(route($camelCaseTable . '.index')));
                } else {

                    //ERROR NO EXISTE LA TABLA
                    $this->error('Error: la tabla ' . $table . ' no existe');
                }
            } else {

                //ERROR NO SE HA ESCRITO PARÁMETRO
                $this->error('Error: no se ha escrito la tabla');
            }
        } catch (\Exception $e) {

            //ERROR CAPTURADO
            $this->error('Error: ' . $e->getMessage());
        }
    }

    //FUNCIÓN PARA CONVERTIR NOMBRE DE TABLA A CAMELCASE
    public function nameToCamelCase($string)
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
