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
        'id',
    ];

    public function scopeId($query, $text)
    {
        return $query->where('id', 'LIKE', '%' . $text . '%');
    }

    public static function formEdit()
    {
        return [
            (Object)[
                'field' => 'name',
                'label' => 'Nombre',
                'type' => 'text'
            ],
        ];
    }

    public static function tableBuilder()
    {
        return [
            (Object)[
                'field' => 'id',
                'label' => '#',
                'filter' => true,
                'filter_type' => 'text',
                'order' => 'id'
            ],
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
