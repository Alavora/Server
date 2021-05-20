<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketIndexResource;
use App\Http\Resources\MarketResource;
use App\Http\Resources\MarketShowResource;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        // return Market::all();
        return MarketIndexResource::collection(Market::all());
    }

    public function show($id)
    {
        // return Market::findOrFail($id);
        $market = Market::findOrFail($id);
        if (null === $market) {
            return abort(404);
        }
        return new MarketShowResource($market);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => 'required|min:3',
            "description" => 'required|min:3',
            "cif" => 'required|min:3',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // 'market_id' => ''
        ]);

        $name = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->store('public/images');

        $market = Market::make($data);
        $market->image_name = $name;
        $market->image_path = $path;
        $market->save();
        return new MarketResource($market);
    }
}
