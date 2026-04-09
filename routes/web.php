<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Route::get('/users', [UserController::class, 'index'])->name('users.show');
    // Route::get('/users-create', [UserController::class, 'create'])->name('users.create');
    // Route::post('/users-store', [UserController::class, 'store'])->name('users.store');
    // Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    // Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    // Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    route::resource('clients',ClientController::class);
    route::resource('users',UserController::class)->middleware('role:admin');
    route::resource('tasks',TaskController::class);
    route::resource('projects',ProjectController::class);
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore')->middleware('role:admin');




    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('notifications/{notification}', [NotificationController::class, 'update'])->name('notifications.update');
    Route::delete('notifications', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    });




require __DIR__ . '/auth.php';
