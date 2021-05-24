<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopIndexResource;
use App\Http\Resources\ShopResource;
use App\Models\Market;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->market_id);
        // return Market::all();
        if ($request->market_id) {
            return ShopIndexResource::collection(Shop::all()->Where("market_id", $request->market_id));
        } else {
            return ShopIndexResource::collection(Shop::all());
        }
    }


    public function indexSeller(Request $request)
    {
        $user = Auth::user();

        return ShopIndexResource::collection(Shop::whereHas('sellers', function ($q) use ($user) {
            $q->where('owner_id', '=', $user->id);
        })->get());
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => 'required|min:3',
            "cif" => 'required|min:3',
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

    public function delete($shop_id)
    {
        Shop::deleted($shop_id);
        return response(null, 204);
    }

    public function show($shop_id)
    {

        return response(null, 204);
    }
}
