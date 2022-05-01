<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use function floatval;
use function intval;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'total' => Transaction::count(),
            'entries' => Transaction::all()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_producto' => 'required',
            'fecha' => 'required',
            'cantidad' => 'required',
            'costo_unitario' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
               'message' => $validator->errors()
            ], 400);
        }


        $product = Product::findOrFail($request->id_producto);
        $stock = $product->stock;
        $product->stock = intval($stock) + intval($request->cantidad);
        $product->save();

        $transaction = new Transaction();
        $transaction->id_producto = intval($request->id_producto);
        $transaction->fecha = $request->fecha;
        $transaction->cantidad = $request->cantidad;
        $transaction->costo_unitario = $request->costo_unitario;
        $transaction->total = floatval($request->cantidad) * floatval($request->costo_unitario);

        $transaction->save();

        return response()->json([
           'ok' => true,
           'entry' => $transaction,
           'message' => 'Entrada registrada correctamente'
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
        $transaction = Transaction::find($id);
        return  $transaction;

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
        $validator = Validator::make($request->all(), [
            'id_producto' => 'required',
            'fecha' => 'required',
            'cantidad' => 'required',
            'costo_unitario' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
               'message' => $validator->errors()
            ], 400);
        }

        $product = Product::findOrFail($request->id_producto);
        $transaction = Transaction::findOrFail($request->id);
        $stock = $product->stock;
        $newStock = intval($stock) - intval($transaction->cantidad);
        $product->stock = intval($newStock) + intval($request->cantidad);
        $product->save();

        $transaction->id_producto = intval($request->id_producto);
        $transaction->fecha = $request->fecha;
        $transaction->cantidad = $request->cantidad;
        $transaction->costo_unitario = $request->costo_unitario;
        $transaction->total = floatval($request->cantidad) * floatval($request->costo_unitario);

        $transaction->save();

        return $transaction;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $transaction = Transaction::findOrFail($id);
        $product = Product::findOrFail($transaction->id_producto);
        $stock = $product->stock;
        $newStock = intval($stock) - intval($transaction->cantidad);
        $product->stock = intval($newStock);
        $product->save();

        Transaction::destroy($id);
        return response()->json([
           'message' => 'Entrada eliminada correctamente'
        ]);

    }
}
