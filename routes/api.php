<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProprieteController;
use App\Http\Controllers\TransactionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/inscription', [AuthController::class, 'inscription']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/getRedirectUrl', [AuthController::class, 'getRedirectUrl']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/biens', [ProprieteController::class, 'index']);
Route::get('biens', [ProprieteController::class, 'indexB']);
Route::post('addbiens', [ProprieteController::class, 'store']);
Route::get('biens/{id}', [ProprieteController::class, 'show']);
Route::put('biens/{id}', [ProprieteController::class, 'update']);
Route::get('/allUser',[UserController::class,'getProprietaire']);
Route::delete('biens/{id}', [ProprieteController::class, 'destroy']);
Route::middleware('auth:api')->get('/transactions', [TransactionController::class, 'index']);
Route::middleware('auth:api')->post('/transactions/filter', [TransactionController::class, 'filterByDate']);
Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
Route::post('/transactions', [TransactionController::class, 'store']);
Route::get('/disponibles', [ProprieteController::class, 'getDispo']);
Route::put('/transactions/{id}/statutTrsansaction', [TransactionController::class, 'updateTransactionStatus']);