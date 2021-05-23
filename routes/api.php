<?php

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource("markets", "App\Http\Controllers\Api\MarketController")->only("index", "show", "store",);
Route::apiResource("shops", "App\Http\Controllers\Api\ShopController")->only("index", "show", "store");
Route::apiResource("products", "App\Http\Controllers\Api\ProductController")->only("index", "show", "store");
Route::apiResource("units", "App\Http\Controllers\Api\UnitController")->only("index", "show", "store");
Route::post("baskets/addproduct", "App\Http\Controllers\Api\BasketController@addProduct");
Route::get("baskets/comment", "App\Http\Controllers\Api\BasketController@getComment");
Route::post("baskets/comment", "App\Http\Controllers\Api\BasketController@postComment");
Route::post("baskets/confirm", "App\Http\Controllers\Api\BasketController@confirm");
Route::get("user/baskets", "App\Http\Controllers\Api\BasketController@shopsBaskets");


//Seller
Route::get("seller/baskets", "App\Http\Controllers\Api\BasketController@index");
// Route::get("baskets/items", "App\Http\Controllers\Api\BasketController@itemGet");
Route::post("seller/baskets/items/confirm", "App\Http\Controllers\Api\BasketController@itemConfirm");
Route::post("seller/baskets/items/update", "App\Http\Controllers\Api\BasketController@itemUpdate");
Route::post("seller/baskets/confirm", "App\Http\Controllers\Api\BasketController@confirm");




//Route::get("markets/{marketId}/shops", "App\Http\Controllers\Api\ShopController@indexByMarket");
// Route::get("shops/{market_id}", "App\Http\Controllers\Api\MarketController@show");