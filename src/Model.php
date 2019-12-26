<?php

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
        'rol' //FILTER EXAMPLE
    ];

    public function scopeId($query, $text)
    {
        return $query->where('id', 'LIKE', '%' . $text . '%');
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
                'field' => 'name', //NAME COLUMN TABLE
                'label' => 'Nombre', //NAME OF FORM
                'type' => 'text' //TEXT | SELECT
            ],
            //EXAMPLE
//            (Object)[
//                'field' => 'rol',
//                'label' => 'Rol',
//                'type' => 'select',
//                'items' => [ //ITEMS SELECT FORM
//                    (Object)[
//                        'label' => 'Administrador',
//                        'value' => 1
//                    ],
//                    (Object)[
//                        'label' => 'Usuario',
//                        'value' => 2
//                    ],
//                ]
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
                'order' => 'id' //FIELD || EMPTY
            ],
            //EXAMPLE
//            (Object)[
//                'field' => 'role',
//                'label' => 'Rol',
//                'filter' => true,
//                'filter_type' => 'select',
//                'filter_items' => [ //ITEMS SELECT FILTER
//                    (Object)[
//                        'label' => 'Administrador',
//                        'value' => 1
//                    ],
//                    (Object)[
//                        'label' => 'Usuario',
//                        'value' => 2
//                    ],
//                ],
//                'order' => 'id'
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
