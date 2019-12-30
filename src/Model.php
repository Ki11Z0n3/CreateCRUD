<?php

/**
 * @package    javimanga/createcrud
 * @author     Javier Manga <javimanga93@gmail.com>
 * @copyright  2019-2019 The FreakSystem Group
 * @license    https://packagist.org/packages/javimanga/createcrud MIT
 * @link       https://packagist.org/packages/javimanga/createcrud
 * @link       https://github.com/Ki11Z0n3/CreateCRUD
 */

namespace App;

use App\Http\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MaxMind\Db\Reader;
use PhpParser\Node\Expr\Cast\Object_;

class :Model extends Model
{
    use Filterable;

    protected $guarded = [];
    protected $appends = [];

    protected $filterable = [
        'id', //FILTER
//        'rol' //FILTER EXAMPLE
    ];

    public function scopeId($query, $text)
    {
        return $query->where('id', $text);
    }

    //SCOPE EXAMPLE
//    public function scopeRol($query, $text)
//    {
//        return $query->where('rol', 'LIKE', '%' . $text . '%');
//    }

    public static function formEdit()
    {
        return [
            (Object)[
                'field' => 'id', //NAME COLUMN TABLE
                'label' => '#', //NAME OF FORM
                'type' => 'text', //TEXT | SELECT
                'placeholder' => 'Escriba aquí' //TEXT VIEW USER FORM
            ],
            //EXAMPLE
//            (Object)[
//                'field' => 'rol',
//                'label' => 'Rol',
//                'type' => 'select',
//                'items' => Rol::all()->map(function($rol){
////                    return (Object)['value' => $rol->id, 'label' => $rol->name];
////                }),
//            ],
        ];
    }

    public static function tableBuilder()
    {
        return [
            (Object)[
                'field' => 'id', //NAME COLUMN TABLE
                'label' => '#', //NAME OF TABLE
                'filter' => true, //TRUE || FALSE
                'filter_type' => 'text', //TEXT | SELECT
                'filter_scope' => 'id', //TEXT | SELECT
                'order' => 'id' //FIELD || EMPTY
            ],
            //EXAMPLE
//            (Object)[
//                'field' => 'role_id',
//                'label' => 'Rol',
//                'filter' => true,
//                'filter_type' => 'select',
//                'filter_scope' => 'rol',
//                'filter_items' => Rol::all()->map(function($rol){
//                    return (Object)['value' => $rol->id, 'label' => $rol->name];
//                }),
//                'order' => 'role_id'
//            ],
            (Object)[
                'field' => 'actions',
                'label' => '',
                'type' => 'actions',
                'items' => (Object)[
                    (Object)[
                        'type' => 'new'
                    ],
//                    (Object)[
//                        'type' => 'show'
//                    ],
                    (Object)[
                        'type' => 'edit'
                    ],
                    (Object)[
                        'type' => 'delete'
                    ],
                ]
            ],
        ];
    }
}
