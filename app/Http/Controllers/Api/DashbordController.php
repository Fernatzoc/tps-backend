<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Product;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashbordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $proveedors = Proveedor::count();
        $categorias = Categoria::count();
        $productos = Product::count();
        $usuarios = User::count();

        $moreStock = DB::table('products')
            ->select('products.nombre', 'products.stock')
            ->orderBy('products.stock', 'desc')
            ->limit(5)
            ->get();


        $movimientos = DB::table('transactions')
            ->join('products', 'products.id', '=', 'transactions.id_producto')
            ->select('transactions.*', 'products.nombre as producto')
            ->orderBy('transactions.fecha', 'desc')
            ->limit(10)
            ->get();

        $topProveedores =  DB::table('products')
            ->join('proveedores', 'proveedores.id', '=', 'products.id_proveedor')
            ->select(DB::raw('proveedores.nombre, count(*) as productos'))
            ->groupBy('proveedores.id')
            ->get();

        $topCategorias =  DB::table('products')
            ->join('categorias', 'categorias.id', '=', 'products.id_categoria')
            ->select(DB::raw('categorias.nombre, count(*) as productos'))
            ->groupBy('categorias.id')
            ->get();

        $reporte = DB::table('transactions')
            ->join('products', 'products.id', '=', 'transactions.id_producto')
            ->join('proveedores', 'proveedores.id', '=', 'products.id_proveedor')
            ->join('categorias', 'categorias.id', '=', 'products.id_categoria')
            ->select('transactions.*', 'products.nombre as producto', 'proveedores.nombre as proveedor', 'categorias.nombre as categoria')
            ->orderBy('transactions.fecha', 'desc')
            ->get();

        return response()->json([
           'count' => [
               'usuarios' => $usuarios,
               'categorias' => $categorias,
               'proveedores' => $proveedors,
               'productos' => $productos
           ],
            'productos' => $moreStock,
            'trasacciones' => $movimientos,
            'proveerdores' => $topProveedores,
            'categorias' => $topCategorias,
            'reporte' => $reporte

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
