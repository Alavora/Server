<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitIndexResource;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller for Unit model
 * @package App\Http\Controllers\Api
 */
class UnitController extends Controller
{
    /**
     * List all Units
     * @return AnonymousResourceCollection 
     */
    public function index()
    {
        // return Market::all();
        return UnitIndexResource::collection(Unit::all());
    }
    /**
     * Returns the Unit with $symbol
     *
     * @param String $symbol
     * @return void
     */
    public function getBySymbol($symbol)
    {
        return new UnitResource(Unit::where('symbol', $symbol));
    }

    /**
     * Stores a Unit
     * @param Request $request containig 'name' and 'symbol'
     * @return UnitResource 
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:units|min:3',
            'symbol' => 'required|unique:units|min:1',
        ]);

        $unit = Unit::make($data);
        // $product->image_path = $path;
        // $product->image_name = $image_name;
        $unit->save();
        return new UnitResource($unit);
    }

    /**
     * Deletes a Unit
     * @param mixed $unit_id Unit to delete
     * @return JsonResponse request status
     */
    public function delete($unit_id)
    {
        Unit::deleted($unit_id);
        return response()->json([
            'successful' => true,
        ]);
    }
}
