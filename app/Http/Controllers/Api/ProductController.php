<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // return Market::all();
        return ProductIndexResource::collection(Product::all()->Where("shop_id", $request->shop_id));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'image_url' => '',
            'price' => 'required',
            'shop_id' => 'required',
            'units' => 'array|required',
        ]);

        $shop = Shop::findOrFail($request->shop_id);

        // $image_name = $request->file('image')->getClientOriginalName();
        // $path = $request->file('image')->store('public/images');

        $units = collect([]);
        while ($unit_id = array_pop($data['units'])) {
            $unit = Unit::findOrFail($unit_id);
            $units->add($unit);
        }

        $product = Product::create($data);
        $product->units = $units;

        // $product->image_path = $path;
        // $product->image_name = $image_name;
        $product->shop()->associate($shop);
        $product->save();
        return new ProductResource($shop);
    }

    public function delete($shop_id)
    {
        Shop::deleted($shop_id);
        return response(null, 204);
    }
}
