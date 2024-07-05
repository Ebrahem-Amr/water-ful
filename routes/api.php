<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
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
    'middleware' => ['assign.guard:admin'],
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
    'middleware' => ['assign.guard:user'],
    'prefix' => 'user'
], function () {

    Route::get('/index',[UserController::class,'index']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::get('/user-profile', [UserController::class, 'userProfile']); 
   
    
});


Route::group([
    'middleware' => ['assign.guard:admin'],
    'prefix' => 'product'
], function () {

    Route::get('/index',[ProductController::class,'index']);
    Route::post('/create_product', [ProductController::class, 'create_product']);
    Route::delete('/delete_product/{id}', [ProductController::class, 'delete_product']);
    Route::patch('/update_product/{id}', [ProductController::class, 'update_product']);


});


    
