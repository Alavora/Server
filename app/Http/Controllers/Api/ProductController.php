<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // return Market::all();
        return ProductIndexResource::collection(Product::all()->Where("shop_id", $request->shop_id));
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|min:3',
            'image_url' => '',
            'price' => '',
            'shop_id' => 'required',
            'units' => 'array|required',
            'units.*' => [
                'required',
            ],
        ]);

        $shop = Shop::findOrFail($request->shop_id);

        //check if user is owner of the shop
        $seller = $shop->sellers()->findOrFail($user->id);

        $product = Product::create($data);
        // $product->units = $units;

        // $product->image_path = $path;
        // $product->image_name = $image_name;
        //$product->shop()->associate($shop);

        $product->save();
        // dd($this->mapUnits($data['units']));

        $product->units()->sync($this->mapUnits($data['units']));
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'image_url' => '',
            'price' => '',
            'shop_id' => 'required',
            'units' => 'array|required',
            'units.*' => [
                'required',
            ],
        ]);

        $shop = Shop::findOrFail($product->shop_id);

        $product->update($data);
        $product->units()->sync($this->mapUnits($data['units']));
        return response()->json([
            'successful' => true,
        ]);
    }


    public function delete($shop_id)
    {
        Shop::deleted($shop_id);
        return response()->json([
            'successful' => true,
        ]);
    }

    private function mapUnits($units)
    {
        // dd($units);
        return collect($units)->map(function ($i) {
            return ['price' => $i];
        });
    }
}
