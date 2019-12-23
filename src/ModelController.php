<?php

namespace App\Http\Controllers;

use App\:Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class :ModelController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validate(request(), [
           'id' => 'sometimes|string',
        ]);

        $:Model = :Model::with([]);
        $:Model->filter($filters);

        if(request()->wantsJson()){
            return $:Model->pagination($request);
        }

        $model = (Object)[
            'component' => 'table-component',
            'title' => '',
            'subtitle' => '',
            'tableBuilder' => :Model::tableBuilder(),
            'prefix' => ':Model',
            'model' => ':Model',
            'relations' => (Object)[],
            'data' => $:Model->paginate(10),
            'filter_column' => '',
            'filter_type' => 'DESC',
            'new' => false
        ];
        return view('template')->with(compact('model'));
    }
}
