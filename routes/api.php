<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::resource('buyer', 'Buyer\BuyerController', ['only' => ['index', 'show']]);
Route::resource('category', 'Category\CategoryController', ['except' => ['create', 'edit']]);
Route::resource('product', 'Product\ProductController', ['only' => ['index', 'show']]);
Route::resource('transaction', 'Transaction\TransactionController', ['only' => ['index', 'show']]);
Route::resource('seller', 'Seller\SellerController', ['only' => ['index', 'show']]);
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);