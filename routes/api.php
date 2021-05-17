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

Route::apiResource("markets", "App\Http\Controllers\Api\MarketController")->only("index", "show");
Route::apiResource("shops", "App\Http\Controllers\Api\ShopController")->only("index", "show");
// Route::get("markets", "App\Http\Controllers\Api\MarketController@index");
// Route::get("shops/{market_id}", "App\Http\Controllers\Api\MarketController@show");