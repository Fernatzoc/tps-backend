<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'total' => Product::count(),
            'products' => Product::all()
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
            'stock' => 'required',
            'id_proveedor' => 'required',
            'id_categoria' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
               'message' => $validator->errors()
            ], 400);
        }

        $product = new Product();
        $product->nombre = $request->nombre;
        $product->stock = $request->stock;
        $product->id_proveedor = $request->id_proveedor;
        $product->id_categoria = $request->id_categoria;

        $product->save();

        return response()->json([
           'ok' => true,
           'product' => $product,
           'message' => 'Producto creado correctamente'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return  $product;
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
            'stock' => 'required',
            'id_proveedor' => 'required',
            'id_categoria' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        $product = Product::findOrFail($request->id);
        $product->nombre = $request->nombre;
        $product->precio = $request->precio;
        $product->stock = $request->stock;
        $product->id_proveedor = $request->id_proveedor;
        $product->id_categoria = $request->id_categoria;

        $product->save();

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json([
           'message' => 'Producto eliminado correctamente'
        ]);
    }
}
