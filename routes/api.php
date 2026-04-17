<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test', function () {
    return "API Working";
});

Route::middleware('auth:    ')->group(function () {

    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/clients', [ClientController::class, 'index'])->name('index');
    Route::post('/clients-store', [ClientController::class, 'store'])->name('store');
    Route::put('/clients-update/{id}', [ClientController::class, 'update'])->name('update');
    Route::delete('/clients-delete/{id}', [ClientController::class, 'delete'])->name('delete');
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
