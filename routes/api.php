<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\SatuanController;
use App\Http\Controllers\API\BarangController;
use App\Http\Controllers\API\TransaksiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('satuan', SatuanController::class);
    Route::apiResource('barang', BarangController::class);
    Route::apiResource('transaksi', TransaksiController::class);
});


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

use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::post('login', [AuthController::class, 'apiLogin']);
Route::post('loginp', [AuthController::class, 'apiLoginP']);
Route::post('daftar', [AuthController::class, 'apiRegister']);

Route::post('register', [AuthController::class, 'register']);

