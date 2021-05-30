<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketIndexResource;
use App\Http\Resources\MarketResource;
use App\Http\Resources\MarketShowResource;
use App\Models\Market;
use Illuminate\Http\Request;

/**
 * It allows to create, list and show markets
 */
class MarketController extends Controller
{
    /**
     * Returns a list of Markets
     *
     * @return MarketIndexResource List of markets
     */
    public function index()
    {
        // return Market::all();
        return MarketIndexResource::collection(Market::all());
    }

    /**
     * Returns a Market with all data necessary to show it
     *
     * @param integer $id
     * @return MarketShowResource
     */
    public function show(int $id): MarketShowResource
    {
        // return Market::findOrFail($id);
        $market = Market::findOrFail($id);
        if (null === $market) {
            return abort(404);
        }
        return new MarketShowResource($market);
    }

    /**
     * Stores a Market and returns it
     *
     * @param Request $request
     * @return MarketResource
     */
    public function store(Request $request): MarketResource
    {
        $data = $request->validate([
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'cif' => 'required|min:3',
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
