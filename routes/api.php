<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\CouponController;
use App\Http\Controllers\api\ContactController;
use App\Http\Controllers\api\PageController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\NewsLetterController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CheckoutController;
use App\Http\Controllers\api\WishlistController;
use App\Http\Controllers\api\UserAddressController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ReturnController;
use App\Http\Controllers\api\TaxRateController;

//Route for register new user
Route::post('register', [UserController::class, 'register']);

//Route for login user
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    //Route for logout user
    Route::post('logout', [UserController::class, 'logout']);
    //Route for Getting user info
    Route::post('userInfo', [UserController::class, 'userInfo']);
    //Route for Edit user info
    Route::post('editUserInfo', [UserController::class, 'editUserInfo']);

    //Route For Get Wishlist Items
    Route::post('getWishlistItems', [WishlistController::class, 'getWishlistItems']);
    //Route For Add Wishlist Items
    Route::post('addWishlistItems', [WishlistController::class, 'addWishlistItems']);
    //Route For Delete Wishlist Items
    Route::post('deleteWishlistItems', [WishlistController::class, 'deleteWishlistItems']);

    //Route For Checking Coupon Validity and Calculating Coupon Discount
    Route::post('checkCoupon', [CouponController::class, 'index']);

    //Route For Get Cart Items
    Route::post('getCartItems', [CartController::class, 'getCartItems']);
    //Route For Add Cart Items
    Route::post('addCartItems', [CartController::class, 'addCartItems']);
    //Route For Delete Cart Items
    Route::post('deleteCartItems', [CartController::class, 'deleteCartItems']);

    //Route For Checkout
    Route::post('checkout', [CheckoutController::class, 'index']);

    //Route For Get UserAddresses
    Route::post('getUserAddresses', [UserAddressController::class, 'index']);
    //Route For Add UserAddresses
    Route::post('addUserAddresses', [UserAddressController::class, 'store']);
    //Route For Edit UserAddresses
    Route::post('editUserAddresses', [UserAddressController::class, 'update']);
    //Route For Delete UserAddresses
    Route::post('deleteUserAddresses', [UserAddressController::class, 'delete']);

    //Route For Get Orders
    Route::post('getOrder', [OrderController::class, 'index']);
    //Route For Storing for Order Data
    Route::post('storeOrder', [OrderController::class, 'store']);

    //Route For Get Return Orders
    Route::post('getReturnOrder', [ReturnController::class, 'index']);
    //Route For Storing for Return Order Data
    Route::post('storeReturnOrder', [ReturnController::class, 'store']);
    
    //Route For User Exempt
    Route::post('taxExempt', [TaxRateController::class, 'taxExempt']);
});

//Route for Forgot password
Route::post('forgotPassword', [UserController::class, 'forgotPassword']);
//Route for Forgot password email
Route::post('forgotPasswordEmail', [UserController::class, 'forgotPasswordEmail']);
//Route for verify email
Route::post('verifyEmail', [UserController::class, 'verifyEmail']);

//Route for Get All Blogs
Route::get('getallblogs', [BlogController::class, 'blogs']);
//Route for Get Blog by Slug
Route::post('getblog', [BlogController::class, 'blog']);

//Route For Getting Page Using slug
Route::post('getPage', [PageController::class, 'index']);

//Route For Getting Data for Home Page
Route::get('home', [HomeController::class, 'index']);

//Route For Storing Newsletter
Route::post('newsletter', [NewsLetterController::class, 'index']);

//Route For Storing Contact Details
Route::post('contact', [ContactController::class, 'contact']);

//Route For Getting Product Using slug
Route::post('getProduct', [ProductController::class, 'index']);
//Route For Getting Product Using Id
Route::post('getProductById', [ProductController::class, 'getProductById']);

//Route For Getting Categories
Route::get('getCategories', [CategoryController::class, 'index']);

//Route For Getting Category Using slug
Route::post('getCategory', [CategoryController::class, 'category']);

//Route for Global Search
Route::post('globalSearch', [HomeController::class, 'globalSearch']);
//Route for Filter Product
Route::post('filterProduct', [HomeController::class, 'filterProduct']);

//Route For Getting Tax Using zip
Route::post('getTaxByZip', [TaxRateController::class, 'getTaxByZip']);
