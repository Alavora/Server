<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopIndexResource;
use App\Http\Resources\ShopResource;
use App\Models\Market;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * Controler for Shop model
 * @package App\Http\Controllers\Api
 */
class ShopController extends Controller
{
    /**
     * Returns all shops, or filtered by market_id if present
     * @param Request $request 
     * @return AnonymousResourceCollection 
     */
    public function index(Request $request)
    {
        // dd($request->market_id);
        // return Market::all();
        if ($request->market_id) {
            return ShopIndexResource::collection(Shop::all()->Where('market_id', $request->market_id));
        } else {
            return ShopIndexResource::collection(Shop::all());
        }
    }

    /**
     * Returns shops whose current user is the owner
     * @return AnonymousResourceCollection 
     */
    public function indexSeller()
    {
        $user = Auth::user();

        return ShopIndexResource::collection(Shop::whereHas('sellers', function ($q) use ($user) {
            $q->where('owner_id', '=', $user->id);
        })->get());
    }

    /**
     * Creates a new Shop
     * @param Request $request containing *'name', *'cif', 'image', *'phone', *'address', 'longitude', 'latitude'
     * @return ShopResource New created Shop
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'cif' => 'required|min:3',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'phone' => 'required|min:3',
            'address' =>  'required|min:3',
            'longitude' => 'min:3',
            'latitude' => 'min:3',
        ]);

        $market = Market::findOrFail($request->market_id);

        // $image_name = $request->file('image')->getClientOriginalName();
        // $path = $request->file('image')->store('public/images');

        $shop = Shop::make($data);
        // $shop->image_path = $path;
        // $shop->image_name = $image_name;
        $shop->market()->associate($market);
        $shop->save();
        return new ShopResource($shop);
    }

    /**
     * Deletes a Shop
     * @param mixed $shop_id 
     * @return JsonResponse 
     */
    public function delete($shop_id)
    {
        Shop::deleted($shop_id);
        return response()->json([
            'successful' => true,
        ]);
    }

    /**
     * Returns a Shop by id
     * @param mixed $shop_id 
     * @return ShopResource 
     */
    public function show($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        return new ShopResource($shop);
    }
}
