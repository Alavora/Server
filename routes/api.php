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
Route::apiResource("units", "App\Http\Controllers\Api\ProductController")->only("index", "show", "store");
Route::get("baskets/add", "App\Http\Controllers\Api\BasketController@addProduct");

//Route::get("markets/{marketId}/shops", "App\Http\Controllers\Api\ShopController@indexByMarket");
// Route::get("shops/{market_id}", "App\Http\Controllers\Api\MarketController@show");