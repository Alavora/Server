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


//User
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::apiResource("users", "App\Http\Controllers\Api\UserController")->only("show", "store", "update")->middleware('auth:sanctum');

//###############
//### client ####
//###############

//Parameters: none
//Returns: A json of id, name, description, cif
Route::apiResource("markets", "App\Http\Controllers\Api\MarketController")->only("index", "show", "store",)->middleware('auth:sanctum');

//Route::apiResource("shops", "App\Http\Controllers\Api\ShopController")->only("index", "show", "store")->middleware('auth:sanctum');
//Parameters: market_id
//Retuns: market's shop, a json with id, name, cif, market_id
Route::get("shops", "App\Http\Controllers\Api\ShopController@index")->middleware('auth:sanctum');

//parameters: quantity, product_id, unit_id
//Returns: nothing, it adds a new item, update it if it was there; or delete it if quantity is zero
Route::post("baskets/addproduct", "App\Http\Controllers\Api\BasketController@addProduct")->middleware('auth:sanctum');

//parameters: none
//Returns: a list of all baskets and products in them, and shop info
Route::get("baskets/all", "App\Http\Controllers\Api\BasketController@shopsBaskets")->middleware('auth:sanctum');

//GET: returns a list of all products, it expects as a parameter shop_id to list only one shop products
//POST: 
// Route::apiResource("products", "App\Http\Controllers\Api\ProductController")->only(["index", "show", "store", "update"])->middleware('auth:sanctum');
Route::apiResource("products", "App\Http\Controllers\Api\ProductController")->middleware('auth:sanctum');
Route::apiResource("units", "App\Http\Controllers\Api\UnitController")->only("index", "show", "store")->middleware('auth:sanctum');
Route::post("baskets/addproduct", "App\Http\Controllers\Api\BasketController@addProduct")->middleware('auth:sanctum');
Route::get("baskets/comment", "App\Http\Controllers\Api\BasketController@getComment")->middleware('auth:sanctum');
Route::post("baskets/comment", "App\Http\Controllers\Api\BasketController@postComment")->middleware('auth:sanctum');
Route::post("baskets/confirm", "App\Http\Controllers\Api\BasketController@confirm")->middleware('auth:sanctum');
Route::get("user/baskets", "App\Http\Controllers\Api\BasketController@shopsBaskets")->middleware('auth:sanctum');


//###############
//### Seller ####
//###############

//Returns logged seller shops. No parameters needed
Route::get("seller/shops", "App\Http\Controllers\Api\ShopController@indexSeller")->middleware('auth:sanctum');
Route::get("seller/baskets", "App\Http\Controllers\Api\BasketController@indexSeller")->middleware('auth:sanctum');
// Route::get("baskets/items", "App\Http\Controllers\Api\BasketController@itemGet")->middleware('auth:sanctum');
Route::post("seller/baskets/items/confirm", "App\Http\Controllers\Api\BasketController@itemConfirm")->middleware('auth:sanctum');
Route::post("seller/baskets/items/update", "App\Http\Controllers\Api\BasketController@itemUpdate")->middleware('auth:sanctum');
Route::post("seller/baskets/confirm", "App\Http\Controllers\Api\BasketController@confirm")->middleware('auth:sanctum');




//deliverer
