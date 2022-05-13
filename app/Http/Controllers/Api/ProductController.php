<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //$product = Product::find($id);
        $product = DB::table('products')
            ->join('proveedores', 'proveedores.id', '=', 'products.id_proveedor')
            ->join('categorias', 'categorias.id', '=', 'products.id_categoria')
            ->select('products.*', 'proveedores.nombre as proveedor', 'categorias.nombre as categoria')
            ->where('products.id', '=', $id)
            ->get();

        $entradas = DB::table('transactions')
            ->select('transactions.*')
            ->where('transactions.movimiento', '=', 'entrada')
            ->where('transactions.id_producto', '=', $id)
            ->get();

        $salidas = DB::table('transactions')
            ->select('transactions.*')
            ->where('transactions.movimiento', '=', 'salida')
            ->where('transactions.id_producto', '=', $id)
            ->get();


        return response()->json([
            'product' => $product,
            'entradas' => $entradas,
            'salidas' => $salidas
        ]);
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
