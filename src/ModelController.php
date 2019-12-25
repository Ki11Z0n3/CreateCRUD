<?php

namespace App\Http\Controllers;

use App\:Model;
use Illuminate\Http\Request;
use Validator;

class :ModelController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validate(request(), [
            'id' => 'sometimes|string',
        ]);

        $:Model = $this->showAll();
        $:Model->filter($filters);

        if (request()->wantsJson()) {
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
            'new' => false,
            'formEdit' => :Model::formEdit()
        ];
        return view(':Model.template')->with(compact('model'));
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

    public function showAll($paginate = false)
    {
        if(!$paginate){
            return :Model::with([]);
        }else {
            return :Model::with([])->paginate(10);
        }
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
