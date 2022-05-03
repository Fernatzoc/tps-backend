<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\TransactionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('login', [AuthController::class, 'login' ]);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('me', [AuthController::class, 'me']);
    Route::get('users',[AuthController::class, 'getAllUsers']);

    Route::get('user/{id}', [AuthController::class, 'show']);
    Route::put('user/{id}', [AuthController::class, 'update']);
    Route::delete('user/{id}', [AuthController::class, 'destroy']);

    Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth.jwt');

});


Route::controller(ProductController::class)->group(function() {
    Route::get('products', 'index');
    Route::post('product', 'store');
    Route::get('product/{id}', 'show');
    Route::put('product/{id}', 'update');
    Route::delete('product/{id}', 'destroy');
});


Route::controller(CategoriaController::class)->group(function() {
    Route::get('categories', 'index');
    Route::post('category', 'store');
    Route::get('category/{id}', 'show');
    Route::put('category/{id}', 'update');
    Route::delete('category/{id}', 'destroy');
});

Route::controller(ProveedorController::class)->group(function() {
    Route::get('providers', 'index');
    Route::post('provider', 'store');
    Route::get('provider/{id}', 'show');
    Route::put('provider/{id}', 'update');
    Route::delete('provider/{id}', 'destroy');
});



Route::controller(TransactionController::class)->group(function() {
    Route::get('transactions', 'index');
    Route::post('transaction', 'store');
    Route::get('transaction/{id}', 'show');
    Route::put('transaction/{id}', 'update');
    Route::delete('transaction/{id}', 'destroy');
});


