<?php

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// routes/api.php


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::apiResource("markets", "App\Http\Controllers\Api\MarketController")->only("index", "show", "store",)->middleware('auth:sanctum');
Route::apiResource("shops", "App\Http\Controllers\Api\ShopController")->only("index", "show", "store")->middleware('auth:sanctum');
Route::apiResource("products", "App\Http\Controllers\Api\ProductController")->only("index", "show", "store")->middleware('auth:sanctum');
Route::apiResource("units", "App\Http\Controllers\Api\UnitController")->only("index", "show", "store")->middleware('auth:sanctum');
Route::post("baskets/addproduct", "App\Http\Controllers\Api\BasketController@addProduct")->middleware('auth:sanctum');
Route::get("baskets/comment", "App\Http\Controllers\Api\BasketController@getComment")->middleware('auth:sanctum');
Route::post("baskets/comment", "App\Http\Controllers\Api\BasketController@postComment")->middleware('auth:sanctum');
Route::post("baskets/confirm", "App\Http\Controllers\Api\BasketController@confirm")->middleware('auth:sanctum');
Route::get("user/baskets", "App\Http\Controllers\Api\BasketController@shopsBaskets")->middleware('auth:sanctum');


//Seller
Route::get("seller/baskets", "App\Http\Controllers\Api\BasketController@index")->middleware('auth:sanctum');
// Route::get("baskets/items", "App\Http\Controllers\Api\BasketController@itemGet")->middleware('auth:sanctum');
Route::post("seller/baskets/items/confirm", "App\Http\Controllers\Api\BasketController@itemConfirm")->middleware('auth:sanctum');
Route::post("seller/baskets/items/update", "App\Http\Controllers\Api\BasketController@itemUpdate")->middleware('auth:sanctum');
Route::post("seller/baskets/confirm", "App\Http\Controllers\Api\BasketController@confirm")->middleware('auth:sanctum');




//Route::get("markets/{marketId}/shops", "App\Http\Controllers\Api\ShopController@indexByMarket");
// Route::get("shops/{market_id}", "App\Http\Controllers\Api\MarketController@show");