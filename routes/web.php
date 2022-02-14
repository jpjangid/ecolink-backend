<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'prevent'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('create', [UserController::class, 'create']);
        Route::post('store', [UserController::class, 'store']);
        Route::get('edit/{id}', [UserController::class, 'edit']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::get('delete/{id}', [UserController::class, 'destroy']);
    });
});

Route::get('statelist', [LocationController::class, 'getState']);
Route::post('citylist', [LocationController::class, 'getCity']);
Route::post('pincodelist', [LocationController::class, 'getPincode']);

require __DIR__ . '/auth.php';
