<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use LogicException;

class ProductSellerController extends Controller
{
    /**
     * List sellers productes. It accept a shop_id as a parameter to filter just one shop
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'shop_id' => 'integer'
        ]);
        //
        $shops = Shop::whereHas('sellers', function ($q) use ($user) {
            $q->where('owner_id', '=', $user->id);
        })->get();
        $shop_ids = $shops->pluck('id');


        // dd($shop_ids);
        // dd((int)($data['shop_id']));

        DB::enableQueryLog();
        if (!array_key_exists('shop_id', $data)) { //list all products from user
            $products = ProductIndexResource::collection(Product::whereHas('shop', function ($q) use ($shop_ids) {
                $q->whereIn('shop_id', $shop_ids);
            })->get());
            return $products;
        } elseif ($shop_ids->contains($data['shop_id'])) {
            $products = ProductIndexResource::collection(Product::where('shop_id', $data['shop_id'])->get());
            // dd(DB::getQueryLog());
            return $products;
        }
        return response()->json(['status_message' => 'Unauthorised'], 401);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

        // check seller access to shop
        $shops = Shop::whereHas('sellers', function ($q) use ($user) {
            $q->where('owner_id', '=', $user->id);
        })->get();
        $shop_ids = $shops->pluck('id');
        if (!$shop_ids->contains($data['shop_id'])) {
            return response()->json(['status_message' => 'Unauthorised'], 401);
        }


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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|min:3',
            'image_url' => '',
            //            'shop_id' => 'required',
            'units' => 'array|required',
            'units.*' => [
                'required',
            ],
        ]);

        // check seller access to product
        $shops = Shop::whereHas('sellers', function ($q) use ($user) {
            $q->where('owner_id', '=', $user->id);
        })->get();
        $shop_ids = $shops->pluck('id');
        if (!$shop_ids->contains($this->shop_id)) {
            return response()->json(['status_message' => 'Unauthorised'], 401);
        }

        $product->update($data);
        $product->units()->sync($this->mapUnits($data['units']));
        return response()->json([
            'successful' => true,
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
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
}
