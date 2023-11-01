<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/transaction/create', [TransactionController::class, 'postTransaction']);
Route::get('/transaction/list/', [TransactionController::class, 'listTransaction']);
Route::delete('/transaction/delete/{id}', [TransactionController::class, 'deleteTransaction']);

Route::post('/product/create', [ProductController::class, 'postProduct']);
Route::get('/product/list', [ProductController::class, 'listProduct']);
Route::delete('/product/delete/{id}', [ProductController::class, 'deleteProduct']);
Route::get('/product/detail/{id}', [ProductController::class, 'detailProduct']);
