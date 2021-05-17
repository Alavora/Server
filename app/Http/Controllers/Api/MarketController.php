<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketIndexResource;
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
}
