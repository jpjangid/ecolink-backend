<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\ContactController;
use App\Http\Controllers\api\PageController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\NewsLetterController;

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

//Route for Blogs
Route::get('getallblogs', [BlogController::class, 'blogs']);
Route::post('getblog', [BlogController::class, 'blog']);

//Route For Get Cart Items
Route::post('getCartItems', [CartController::class, 'getCartItems']);

//Route For Getting Page Using slug
Route::post('getPage', [PageController::class, 'index']);

//Route For Getting Data for Home Page
Route::post('home', [HomeController::class, 'index']);

//Route For Storing Newsletter
Route::post('newsletter', [NewsLetterController::class, 'index']);

//Route For Storing Contact Details
Route::post('contact', [ContactController::class, 'contact']);