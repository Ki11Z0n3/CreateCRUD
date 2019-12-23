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

    protected
    $guarded = [];
    protected
    $appends = [];

    protected
    $filterable = [
        'id',
        //// EXAMPLE ////
//        'year'
        //// EXAMPLE ////
    ];

    public
    function scopeId($query, $text)
    {
        return $query->where('id', 'LIKE', '%' . $text . '%');
    }

    //// EXAMPLE ////
//    public function scopeYear($query, $text)
//    {
//        return $query->where('year', 'LIKE', '%' . $text . '%');
//    }
    //// EXAMPLE ////

    public
    static function tableBuilder()
    {
        return [
            (Object)[
                'field' => 'id',
                'label' => '#',
                'filter' => true,
                'filter_type' => 'text',
                'order' => 'id'
            ],
            //// EXAMPLES ////
//            (Object)[
//                'field' => 'image',
//                'label' => 'Image',
//                'filter' => false, // false || true
//                'filter_type' => 'text',
//                'order' => '' // year || empty
//                'urlLabel' => 'Link to image', // Comment to disabled
//                'color' => 'red', // Comment to disabled
//                'backgroundColor' => 'black', // Comment to disabled
//                'type' => 'url', // Comment to disabled
//            ],
//            (Object)[
//                'field' => 'device',
//                'label' => 'Dispositivo',
//                'filter' => true,
//                'filter_type' => 'select',
//                'filter_items' => collect(DB::select('select device from visitors group by device'))->map(function($value){
//                    return (Object)['label' => $value->device, 'value' => $value->device];
//                }),
//                'order' => 'device'
//            ],
            //// EXAMPLES ////
            (Object)[
                'field' => 'actions',
                'label' => '',
                'type' => 'actions',
                'items' => (Object)[
//                    (Object)[
//                        'type' => 'show'
//                    ],
//                    (Object)[
//                        'type' => 'edit'
//                    ],
//                    (Object)[
//                        'type' => 'delete'
//                    ],
                ]
            ],
        ];
    }
}
