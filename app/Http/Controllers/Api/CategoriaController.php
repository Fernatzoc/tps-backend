<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Validator;

class CategoriaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'total' => Categoria::count(),
            'categories' => Categoria::all()
        ]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'nombre' => 'required|unique:categorias'
        ]);

        if($validator->fails()) {
             return response()->json([
                 'message' => $validator->errors()
             ],400);
        }

        $category = new Categoria();
        $category->nombre = $request->nombre;
        $category->save();

        return response()->json([
            'ok' => true,
            'category' => $category,
            'message' => 'Categoria creada correctamente'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Categoria::find($id);
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:categorias'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ],400);
        }

        $category = Categoria::findOrFail($request->id);
        $category->nombre = $request->nombre;
        $category->save();

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Categoria::destroy($id);
        return response()->json([
            'message' => 'Categoria eliminada correctamente'
        ]);
    }
}
