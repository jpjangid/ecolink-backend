<?php

use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\AskChemistController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\RequestProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\TaxRateController;
use App\Http\Controllers\BulkPricingController;
use App\Http\Controllers\TechnicalSupportController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\DropshipController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaticValueController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FormDataController;

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
    return redirect()->route('login');
})->middleware('prevent-back-history');

Auth::routes();

// Route::get('/artisan/clear', function () {
//     \Artisan::call('cache:clear');
//     \Artisan::call('route:clear');
//     \Artisan::call('view:clear');
//     \Artisan::call('config:clear');
//     \Artisan::call('optimize:clear');
// });

Route::middleware(['auth', 'prevent-back-history'])->prefix('admin')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    //Profile Route
    Route::get('profile/{id}', [ProfileController::class, 'edit']);
    Route::patch('profile/update/{id}', [ProfileController::class, 'update']);
    Route::post('/summernote', [PageController::class, 'summernote']);



    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('create', [UserController::class, 'create']);
        Route::post('store', [UserController::class, 'store']);
        Route::get('edit/{id}', [UserController::class, 'edit']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::get('delete/{id}', [UserController::class, 'destroy']);
        Route::post('/update_status', [UserController::class, 'update_status']);
        Route::post('/userinfo', [UserController::class, 'userinfo']);
    });

    Route::prefix('blogcategory')->group(function () {
        Route::get('/', [BlogCategoryController::class, 'index']);
        Route::get('/create', [BlogCategoryController::class, 'create']);
        Route::post('/store', [BlogCategoryController::class, 'store']);
        Route::get('/edit/{id}', [BlogCategoryController::class, 'edit']);
        Route::put('/update/{id}', [BlogCategoryController::class, 'update']);
        Route::delete('/delete/{id}', [BlogCategoryController::class, 'destroy']);
        Route::post('/update_status', [BlogCategoryController::class, 'update_status']);
    });
    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('/create', [BlogController::class, 'create']);
        Route::post('/store', [BlogController::class, 'store']);
        Route::get('/edit/{id}', [BlogController::class, 'edit']);
        Route::put('/update/{id}', [BlogController::class, 'update']);
        Route::delete('/delete/{id}', [BlogController::class, 'destroy']);
        Route::post('/update_status', [BlogController::class, 'update_status']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/create', [CategoryController::class, 'create']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit']);
        Route::put('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
        Route::post('/update_status', [CategoryController::class, 'update_status']);
    });

    Route::prefix('sub/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index_sub']);
        Route::get('/create', [CategoryController::class, 'create_sub']);
        Route::post('/store', [CategoryController::class, 'store_sub']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit_sub']);
        Route::put('/update/{id}', [CategoryController::class, 'update_sub']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy_sub']);
        Route::post('/update_status', [CategoryController::class, 'update_status_sub']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/create', [ProductController::class, 'create']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/edit/{id}', [ProductController::class, 'edit']);
        Route::put('/update/{id}', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
        Route::post('/update_status', [ProductController::class, 'update_status']);
    });

    Route::prefix('newsletters')->group(function () {
        Route::get('/', [NewsLetterController::class, 'index']);
        Route::get('/create', [NewsLetterController::class, 'create']);
        Route::post('/store', [NewsLetterController::class, 'store']);
        Route::get('/edit/{id}', [NewsLetterController::class, 'edit']);
        Route::put('/update/{id}', [NewsLetterController::class, 'update']);
        Route::delete('/delete/{id}', [NewsLetterController::class, 'destroy']);
    });

    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'index']);
        Route::get('/create', [PageController::class, 'create']);
        Route::post('/store', [PageController::class, 'store']);
        Route::get('/edit/{id}', [PageController::class, 'edit']);
        Route::put('/update/{id}', [PageController::class, 'update']);
        Route::delete('/delete/{id}', [PageController::class, 'destroy']);
        Route::get('/copy/{id}', [PageController::class, 'copy']);
        Route::post('/update_status', [PageController::class, 'update_status']);
    });

    Route::prefix('coupons')->group(function () {
        Route::get('/', [CouponController::class, 'index']);
        Route::get('/create', [CouponController::class, 'create']);
        Route::post('/store', [CouponController::class, 'store']);
        Route::get('/edit/{id}', [CouponController::class, 'edit']);
        Route::put('/update/{id}', [CouponController::class, 'update']);
        Route::delete('/delete/{id}', [CouponController::class, 'destroy']);
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
        Route::get('/create', [OrderController::class, 'create']);
        Route::post('/store', [OrderController::class, 'store']);
        Route::get('/edit/{id}', [OrderController::class, 'edit']);
        Route::put('/update/{id}', [OrderController::class, 'update']);
        Route::get('/delete/{id}', [OrderController::class, 'destroy']);
        Route::get('/order_detail/{id}', [OrderController::class, 'order_detail']);
        Route::post('/update_detail', [OrderController::class, 'update_detail']);
        Route::post('/getAddresses', [OrderController::class, 'getAddresses']);
        Route::post('/getAddressDetail', [OrderController::class, 'getAddressDetail']);
        Route::post('/getProductById', [OrderController::class, 'getProductById']);
        Route::post('/static_value', [OrderController::class, 'StaticValue']);
        Route::post('/getHazardous', [OrderController::class, 'getHazardous']);
        Route::post('/getCouponCode', [OrderController::class, 'getCouponCode']);
        Route::post('/codeApplied', [OrderController::class, 'codeApplied']);
        Route::post('/getShippingCharge', [OrderController::class, 'getShippingCharge']);
        Route::post('/getTaxableAmount', [OrderController::class, 'getTaxableAmount']);
    });

    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/return_detail/{id}', [ReturnController::class, 'return_detail']);
        Route::post('/update', [ReturnController::class, 'update']);
    });

    Route::prefix('askchemist')->group(function () {
        Route::get('/', [AskChemistController::class, 'index']);
        Route::get('/show/{id}', [AskChemistController::class, 'show']);
        Route::get('/edit/{id}', [AskChemistController::class, 'edit']);
        Route::put('/update/{id}', [AskChemistController::class, 'update']);
        Route::delete('/delete/{id}', [AskChemistController::class, 'destroy']);
    });

    Route::prefix('contact')->group(function () {
        Route::get('/', [ContactUsController::class, 'index']);
        Route::get('/show/{id}', [ContactUsController::class, 'show']);
        Route::get('/edit/{id}', [ContactUsController::class, 'edit']);
        Route::put('/update/{id}', [ContactUsController::class, 'update']);
        Route::delete('/delete/{id}', [ContactUsController::class, 'destroy']);
    });

    Route::prefix('requestproduct')->group(function () {
        Route::get('/', [RequestProductController::class, 'index']);
        Route::get('/show/{id}', [RequestProductController::class, 'show']);
        Route::get('/edit/{id}', [RequestProductController::class, 'edit']);
        Route::put('/update/{id}', [RequestProductController::class, 'update']);
        Route::delete('/delete/{id}', [RequestProductController::class, 'destroy']);
    });

    Route::prefix('bulkpricing')->group(function () {
        Route::get('/', [BulkPricingController::class, 'index']);
        Route::get('/show/{id}', [BulkPricingController::class, 'show']);
        Route::get('/edit/{id}', [BulkPricingController::class, 'edit']);
        Route::put('/update/{id}', [BulkPricingController::class, 'update']);
        Route::delete('/delete/{id}', [BulkPricingController::class, 'destroy']);
    });

    Route::prefix('technicalsupport')->group(function () {
        Route::get('/', [TechnicalSupportController::class, 'index']);
        Route::get('/show/{id}', [TechnicalSupportController::class, 'show']);
        Route::get('/edit/{id}', [TechnicalSupportController::class, 'edit']);
        Route::put('/update/{id}', [TechnicalSupportController::class, 'update']);
        Route::delete('/delete/{id}', [TechnicalSupportController::class, 'destroy']);
    });

    Route::prefix('taxrates')->group(function () {
        Route::get('/', [TaxRateController::class, 'index']);
        Route::get('/create', [TaxRateController::class, 'create']);
        Route::post('/store', [TaxRateController::class, 'store']);
        Route::get('/edit/{id}', [TaxRateController::class, 'edit']);
        Route::put('/update/{id}', [TaxRateController::class, 'update']);
        Route::delete('/delete/{id}', [TaxRateController::class, 'destroy']);
    });

    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/create', [CartController::class, 'create']);
        Route::post('/store', [CartController::class, 'store']);
        Route::get('/edit/{id}', [CartController::class, 'edit']);
        Route::put('/update/{id}', [CartController::class, 'update']);
        Route::delete('/delete/{id}', [CartController::class, 'destroy']);
    });

    Route::prefix('wishlists')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::delete('/delete/{id}', [WishlistController::class, 'destroy']);
    });

    Route::prefix('addresses')->group(function () {
        Route::get('/', [UserAddressController::class, 'index']);
        Route::delete('/delete/{id}', [UserAddressController::class, 'destroy']);
    });

    Route::prefix('notices')->group(function () {
        Route::get('/', [NoticeController::class, 'index']);
        Route::get('/edit/{id}', [NoticeController::class, 'edit']);
        Route::put('/update/{id}', [NoticeController::class, 'update']);
        Route::post('/update_status', [NoticeController::class, 'update_status']);
    });


    /**Route for userpermissions */
    Route::prefix('userpermissions')->group(function () {
        Route::get('/', [UserPermissionController::class, 'index']);
        Route::post('store', [UserPermissionController::class, 'store']);
        Route::get('edit/{id}', [UserPermissionController::class, 'edit']);
    });

    Route::prefix('staticvalues')->group(function () {
        Route::get('/', [StaticValueController::class, 'index']);
        // Route::get('/create', [StaticValueController::class, 'create']);
        // Route::post('/store', [StaticValueController::class, 'store']);
        Route::get('/edit/{id}', [StaticValueController::class, 'edit']);
        Route::put('/update/{id}', [StaticValueController::class, 'update']);
        Route::delete('/delete/{id}', [StaticValueController::class, 'destroy']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('/edit/{category_title}/{category_des}', [SettingController::class, 'edit']);
        Route::put('/update/{category_title}/{category_des}', [SettingController::class, 'update']);
    });

    Route::prefix('reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'salesReport']);
        Route::get('/carts', [ReportController::class, 'abandonedCartReport']);
    });

    Route::prefix('dropship')->group(function () {
        Route::get('/', [DropshipController::class, 'index']);
        Route::get('/create', [DropshipController::class, 'create']);
        Route::post('/store', [DropshipController::class, 'store']);
        Route::get('/edit/{id}', [DropshipController::class, 'edit']);
        Route::put('/update/{id}', [DropshipController::class, 'update']);
        Route::delete('/delete/{id}', [DropshipController::class, 'destroy']);
    });

    Route::get('form/detail/{id}',[FormDataController::class,'show']);
    Route::get('forms/list',[FormDataController::class,'index']);
    Route::get('forms/list/{form_data_id}',[FormDataController::class,'list']);
});

Route::get('qboCustomer/{companyName}/{user_id}', [UserController::class, 'qboCustomer']);
Route::get('statelist', [LocationController::class, 'getState']);
Route::post('citylist', [LocationController::class, 'getCity']);
Route::post('pincodelist', [LocationController::class, 'getPincode']);
