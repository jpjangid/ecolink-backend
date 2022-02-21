<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NewsLetterController;


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


Auth::routes();
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'prevent'])->prefix('admin')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('create', [UserController::class, 'create']);
        Route::post('store', [UserController::class, 'store']);
        Route::get('edit/{id}', [UserController::class, 'edit']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::get('delete/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('/create', [BlogController::class, 'create']);
        Route::post('/store', [BlogController::class, 'store']);
        Route::get('/edit/{id}', [BlogController::class, 'edit']);
        Route::put('/update/{id}', [BlogController::class, 'update']);
        Route::get('/delete/{id}', [BlogController::class, 'destroy']);
        Route::post('/update_status', [BlogController::class, 'update_status']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/create', [CategoryController::class, 'create']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit']);
        Route::put('/update/{id}', [CategoryController::class, 'update']);
        Route::get('/delete/{id}', [CategoryController::class, 'destroy']);
        Route::post('/update_status', [CategoryController::class, 'update_status']);
    });

    Route::prefix('sub/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index_sub']);
        Route::get('/create', [CategoryController::class, 'create_sub']);
        Route::post('/store', [CategoryController::class, 'store_sub']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit_sub']);
        Route::put('/update/{id}', [CategoryController::class, 'update_sub']);
        Route::get('/delete/{id}', [CategoryController::class, 'destroy_sub']);
        Route::post('/update_status', [CategoryController::class, 'update_status_sub']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/create', [ProductController::class, 'create']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/edit/{id}', [ProductController::class, 'edit']);
        Route::put('/update/{id}', [ProductController::class, 'update']);
        Route::get('/delete/{id}', [ProductController::class, 'destroy']);
        Route::post('/update_status', [ProductController::class, 'update_status']);
    });

    Route::prefix('newsletters')->group(function () {
        Route::get('/', [NewsLetterController::class, 'index']);
        Route::get('/create', [NewsLetterController::class, 'create']);
        Route::post('/store', [NewsLetterController::class, 'store']);
        Route::get('/edit/{id}', [NewsLetterController::class, 'edit']);
        Route::put('/update/{id}', [NewsLetterController::class, 'update']);
        Route::get('/delete/{id}', [NewsLetterController::class, 'destroy']);
    });
});

Route::get('statelist', [LocationController::class, 'getState']);
Route::post('citylist', [LocationController::class, 'getCity']);
Route::post('pincodelist', [LocationController::class, 'getPincode']);
