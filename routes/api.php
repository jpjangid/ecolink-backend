<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\ContactController;
use App\Http\Controllers\api\PageController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\NewsLetterController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CheckoutController;

//Route for register new user
Route::post('/register', [UserController::class, 'register']);

//Route for login user
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    //Route for logout user
    Route::post('/logout', [UserController::class, 'logout']);

    //Route For Get Cart Items
    Route::post('getCartItems', [CartController::class, 'getCartItems']);
    //Route For Get Cart Items
    Route::post('addCartItems', [CartController::class, 'addCartItems']);
    //Route For Checkout
    Route::post('checkout', [CheckoutController::class, 'index']);
});

//Route for Get All Blogs
Route::get('getallblogs', [BlogController::class, 'blogs']);
//Route for Get Blog by Slug
Route::post('getblog', [BlogController::class, 'blog']);

//Route For Getting Page Using slug
Route::post('getPage', [PageController::class, 'index']);

//Route For Getting Data for Home Page
Route::post('home', [HomeController::class, 'index']);

//Route For Storing Newsletter
Route::post('newsletter', [NewsLetterController::class, 'index']);

//Route For Storing Contact Details
Route::post('contact', [ContactController::class, 'contact']);

//Route For Getting Product Using slug
Route::post('getProduct', [ProductController::class, 'index']);

//Route For Getting Categories
Route::get('getCategories', [CategoryController::class, 'index']);

//Route For Getting Category Using slug
Route::post('getCategory', [CategoryController::class, 'category']);