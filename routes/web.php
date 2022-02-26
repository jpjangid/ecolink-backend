<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturnController;


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
    return redirect()->route('login');
})->middleware('prevent-back-history');

Route::get('/artisan/clear', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    \Artisan::call('optimize:clear');
});

Route::middleware(['auth', 'prevent-back-history'])->prefix('admin')->group(function () {
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

    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'index']);
        Route::get('/create', [PageController::class, 'create']);
        Route::post('/store', [PageController::class, 'store']);
        Route::get('/edit/{id}', [PageController::class, 'edit']);
        Route::put('/update/{id}', [PageController::class, 'update']);
        Route::get('/delete/{id}', [PageController::class, 'destroy']);
        Route::post('/update_status', [PageController::class, 'update_status']);
    });

    Route::prefix('coupons')->group(function () {
        Route::get('/', [CouponController::class, 'index']);
        Route::get('/create', [CouponController::class, 'create']);
        Route::post('/store', [CouponController::class, 'store']);
        Route::get('/edit/{id}', [CouponController::class, 'edit']);
        Route::put('/update/{id}', [CouponController::class, 'update']);
        Route::get('/delete/{id}', [CouponController::class, 'destroy']);
        Route::post('/update_status', [CouponController::class, 'update_status']);
    });

    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::get('/create', [WalletController::class, 'create']);
        Route::post('/store', [WalletController::class, 'store']);
        Route::get('/edit/{id}', [WalletController::class, 'edit']);
        Route::put('/update/{id}', [WalletController::class, 'update']);
        Route::get('/delete/{id}', [WalletController::class, 'destroy']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/order_detail/{id}', [OrderController::class, 'order_detail']);
        Route::post('/update', [OrderController::class, 'update']);
    });

    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/return_detail/{id}', [ReturnController::class, 'return_detail']);
        Route::post('/update', [ReturnController::class, 'update']);
    });
});

Route::get('statelist', [LocationController::class, 'getState']);
Route::post('citylist', [LocationController::class, 'getCity']);
Route::post('pincodelist', [LocationController::class, 'getPincode']);
