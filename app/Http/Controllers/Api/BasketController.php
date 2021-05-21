<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasketIndexResource;
use App\Http\Resources\BasketResource;
use App\Http\Resources\BasketShowResource;
use App\Models\Basket;
use App\Models\Item;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BasketIndexResource::collection(Basket::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            /** TODO **/
            // "name" => 'required|min:3',
            // "description" => 'required|min:3',
            // "cif" => 'required|min:3',
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // // 'market_id' => ''
        ]);



        $basket = Basket::make($data);

        $basket->save();
        return new BasketResource($basket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $basket = Basket::findOrFail($id);
        if (null === $basket) {
            return abort(404);
        }
        return new BasketShowResource($basket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Basket $basket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Basket  $basket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Basket $basket)
    {
        $basket->delete();
    }

    /**
     * Creates an item and adds it to the basket
     *
     * @param Response $response
     * @return void
     */
    public function addProduct(Request $request)
    {

        $values = $request->validate([
            'quantity' => 'required|numeric',
            'product_id' => 'required|integer',
            'unit_id' => 'required|integer',
        ]);
        $product = Product::findOrFail($request->product_id);

        $unit = Unit::findOrFail($request->unit_id);



        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $product->shop_id,

        ]);



        $item = new Item;
        $item->product_id = $product->id;
        $item->unit_id = $unit->id;
        $item->price = $product->price;


        // dd($basket);

        $basket->items()->save($item);
    }


    public function getComment(Request $request)
    {
        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $request->shop_id,
        ]);
        return response()->json([
            'data' => $basket->comments
        ]);
    }

    public function postComment(Request $request)
    {
        $values = $request->validate([
            'comments' => 'required',
            'shop_id' => 'required|integer',
        ]);
        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $request->shop_id,
        ]);
        $basket->comments = $values['comments'];
        $basket->save();
        return response()->json(['return' => True], 202);
    }

    /**
     * Confirms de basket to be prepared by shop
     *
     * @param Request $request it should contain a valid shop_id
     * @return void
     */
    public function confirm(Request $request)
    {
        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $request->shop_id,
        ]);
        $basket->status = Basket::STATUS_CONFIRMED;
        $basket->save();
    }
}
