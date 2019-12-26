<?php

namespace App\Http\Controllers;

use App\:Model;
use Illuminate\Http\Request;
use Validator;

class :ModelController extends Controller
{
    protected static $relations = [];

    public function index(Request $request)
    {
        $filters = $this->validate(request(), [ //VALIDATE FILTERS
            'id' => 'sometimes|string', //FILTER
//            'rol' => 'sometimes|string', //FILTER EXAMPLE
        ]);

        $:Model = $this->showAll(); //METHOD GET DATA
        $:Model->filter($filters); //METHOD FILTER

        if (request()->wantsJson()) { //IF JSON RETURN DATA FILTER AND PAGINATION
            return $:Model->pagination($request);
        }

        $model = (Object)[
            'title' => 'CRUD :Model', //TITLE CARD
            'subtitle' => ':Model', //SUBTITLE CARD
            'tableBuilder' => :Model::tableBuilder(), //METHOD BUILD TABLE
            'formEdit' => :Model::formEdit(), //METHOD BUILD FORM EDIT/NEW
            'prefix' => ':Model', //NAME PREFIX ROUTE
            'data' => $:Model->paginate(10), //DATA BUILD TABLE
            'filter_column' => 'id', //ORDER COLUMN TABLE
            'filter_type' => 'DESC', //ORDER TABLE
        ];
        return view(':Model.template')->with(compact('model')); //RETURN VIEW BLADE
    }

    public function showAll($paginate = false)
    {
        if(!$paginate){
            return :Model::with(self::$relations);
        }else {
            return :Model::with(self::$relations)->paginate(10);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validate();

        :Model::create($data);

        return response()->json([
            'data' => $this->showAll(true),
            'message' => 'Se ha creado correctamente'
        ]);
    }

    public function show(:Model $:Model)
    {
        abort(404);
    }

    public function update(Request $request, :Model $:Model)
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validate();

        $:Model->update($data);

        return response()->json([
            'data' => $this->showAll(true),
            'message' => 'Se ha actualizado correctamente'
        ]);
    }

    public function destroy(:Model $:Model)
    {
        $:Model->delete();
        return response()->json([
            'data' => $this->showAll(true),
            'message' => 'Se ha borrado correctamente'
        ]);
    }
}
