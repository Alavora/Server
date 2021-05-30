<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use LogicException;

/**
 * Allow to create, list, update and destroy Products
 */
class ProductController extends Controller
{
    /**
     * List all products for a shop
     *
     * @param Request $request with shoip_id as a parameter
     * @return ProductIndexResource::collection with all the products of the shop
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'shop_id' => 'required|int',
        ]);
        return ProductIndexResource::collection(Product::all()->Where('shop_id', $data['shop_id']));
    }

    /**
     * Stores a new product, after checking the user has access to the shop.
     *
     * @param Request $request containing: name, shop_id, and units (which is an array where units[unit_id]=price)
     * @return JsonResponse with the new product data
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|min:3',
            'image_url' => '',
            'shop_id' => 'required',
            'units' => 'array|required',
            'units.*' => [
                'required',
            ],
        ]);

        $shop = Shop::findOrFail($request->shop_id);

        //check if user is owner of the shop
        // $seller = $shop->sellers()->findOrFail($user->id);

        $product = Product::create($data);
        $product->save();
        $product->units()->sync($this->mapUnits($data['units']));
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Updates a product
     *
     * @param Request $request containing 'name', 'shop_id' and 'units'
     * @param Product $product to update
     * @return Json with request status and product
     */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|min:3',
            'image_url' => '',
            'shop_id' => 'required',
            'units' => 'array|required',
            'units.*' => [
                'required',
            ],
        ]);

        // $shop = Shop::findOrFail($product->shop_id);
        // $seller = $shop->sellers()->findOrFail($user->id);

        $product->update($data);
        $product->units()->sync($this->mapUnits($data['units']));
        return response()->json([
            'successful' => true,
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Deletes a product
     * @param Product $product 
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        $user = Auth::user();
        $owner = Shop::findOrFail($product->shop_id)->sellers()->find($user->id);
        if (empty($owner)) {
            return response()->json(['status_message' => 'Unauthorised'], 401);
        }
        $product->units()->detach();
        $product->delete();
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
