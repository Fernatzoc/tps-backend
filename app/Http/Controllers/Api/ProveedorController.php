<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Validator;

class ProveedorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'total' => Proveedor::count(),
            'proveedores' => Proveedor::all()
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
            'nombre' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email|unique:proveedores',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ],400);
        }

        $provider = new Proveedor();
        $provider->nombre = $request->nombre;
        $provider->telefono = $request->telefono;
        $provider->direccion = $request->direccion;
        $provider->correo = $request->correo;
        $provider->save();

        return response()->json([
            'ok' => true,
            'proveedor' => $provider,
            'message' => 'Proveedor creada correctamente'
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
        $provider = Proveedor::find($id);
        return $provider;
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
            'nombre' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email|unique:proveedores',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ],400);
        }

        $provider = Proveedor::findOrFail($request->id);
        $provider->nombre = $request->nombre;
        $provider->telefono = $request->telefono;
        $provider->direccion = $request->direccion;
        $provider->correo = $request->correo;
        $provider->save();

        return $provider;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Proveedor::destroy($id);
        return response()->json([
            'message' => 'Proveedor eliminada correctamente'
        ]);
    }
}
