<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, $shop_id)
    {
        // return Market::all();
        return ProductIndexResource::collection(Product::all()->Where("market_id", $shop_id));
    }
}
