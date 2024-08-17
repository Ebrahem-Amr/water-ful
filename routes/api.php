<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerServiceController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserCartController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserPurchaseController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::group([
    'prefix' => 'admin'
], function () {

    Route::get('/index',[AdminController::class,'index']);
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::post('/refresh', [AdminController::class, 'refresh']);
    Route::get('/admin-profile', [AdminController::class, 'adminProfile']); 
   
    
});

Route::group([
    'prefix' => 'customerservice'
], function () {

    Route::get('/index',[CustomerServiceController::class,'index']);
    Route::post('/login', [CustomerServiceController::class, 'login']);
    Route::post('/register', [CustomerServiceController::class, 'register']);
    Route::post('/logout', [CustomerServiceController::class, 'logout']);
    Route::post('/refresh', [CustomerServiceController::class, 'refresh']);
    Route::get('/customerservice-profile', [CustomerServiceController::class, 'customerserviceProfile']); 
   
    
});

Route::group([
    'prefix' => 'user'
], function () {

    Route::get('/index',[UserController::class,'index']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/verify', [UserController::class, 'verify']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::get('/user-profile', [UserController::class, 'userProfile']); 
   
    
});


Route::group([
    'prefix' => 'product'
], function () {

    Route::get('/index',[ProductController::class,'index']);
    Route::post('/create_product', [ProductController::class, 'create_product']);
    Route::delete('/delete_product/{id}', [ProductController::class, 'delete_product']);
    Route::patch('/update_product/{id}', [ProductController::class, 'update_product']);


});

Route::group([
    'prefix' => 'category'
], function () {

    Route::get('/index',[CategoryController::class,'index']);
    Route::post('/create_category', [CategoryController::class, 'create_category']);
    Route::delete('/delete_category/{id}', [CategoryController::class, 'delete_category']);
    Route::patch('/update_category/{id}', [CategoryController::class, 'update_category']);


});


Route::group([
    'prefix' => 'cart'
], function () {

    Route::get('/index',[UserCartController::class,'index']);
    Route::post('/create', [UserCartController::class, 'create']);
    Route::delete('/delete/{id}', [UserCartController::class, 'delete']);

});

Route::group([
    'prefix' => 'UserPurchase'
], function () {

    Route::get('/index',[UserPurchaseController::class,'index']);
    Route::get('/userPurchases',[UserPurchaseController::class,'userPurchases']);
    Route::post('/create', [UserPurchaseController::class, 'create']);
    Route::delete('/delete/{id}', [UserPurchaseController::class, 'delete']);

});

Route::group([
    'prefix' => 'message'
], function () {

    Route::get('/getMessageOfCustomerService/{id}',[MessageController::class,'getMessageOfCustomerService']);
    Route::post('/storeMessageOfCustomerService/{id}',[MessageController::class,'storeMessageOfCustomerService']);
    Route::get('/getMessageOfUser',[MessageController::class,'getMessageOfUser']);
    Route::post('/storeMessageOfUser',[MessageController::class,'storeMessageOfUser']);

});



    
    
