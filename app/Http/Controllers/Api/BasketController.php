<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasketIndexResource;
use App\Http\Resources\BasketResource;
use App\Http\Resources\BasketShowResource;
use App\Http\Resources\ShopsBasketsResource;
use App\Models\Basket;
use App\Models\Item;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            /** TODO **/
            "shop_id" => 'required|integer',
            // "description" => 'required|min:3',
            // "cif" => 'required|min:3',
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // // 'market_id' => ''
        ]);
        return BasketIndexResource::collection(Basket::where('shop_id', $data['shop_id'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            /** TODO **/
            // "name" => 'required|min:3',
            // "description" => 'required|min:3',
            // "cif" => 'required|min:3',
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // // 'market_id' => ''
        ]);

        $basket = Basket::make($data);
        $basket->user()->associate($user);
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
        $user = $request->user();
        $values = $request->validate([
            'quantity' => 'required|numeric',
            'product_id' => 'required|integer',
            'unit_id' => 'required|integer',
        ]);
        $product = Product::findOrFail($request->product_id);

        $unit = Unit::findOrFail($request->unit_id);
        // DB::enableQueryLog();

        $basket = Basket::where([
            ['shop_id', $product->shop_id],
            ['user_id', $user->id],
            ['status', Basket::STATUS_UNCONFIRMED],
            ['user_id', $user->id]
        ])->first();
        // $basket = Basket::firstOrCreate([
        //     'shop_id' => $product->shop_id,
        //     'user_id' => $user->id,
        //     'status' => Basket::STATUS_UNCONFIRMED,
        //     'user_id' => $user->id
        // ]);

        if ($basket == null) {
            $data = [
                'shop_id' => $product->shop_id,
                'user_id' => $user->id,
                'status' => Basket::STATUS_UNCONFIRMED,
                'user_id' => $user->id
            ];
            $basket = Basket::make($data);
            $basket->save();
        }
        // dd(DB::getQueryLog());

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
        return response()->noContent(); // 204
    }

    public function itemConfirm(Request $request)
    {
        $data = $request->validate([
            "item_id" => 'required|integer',
        ]);
        $item = Item::find($data['item_id']);

        $item->status = Item::STATUS_CONFIRMED;
        $item->save();
        return response()->noContent(); // 204
    }

    public function itemUpdate(Request $request)
    {
        $data = $request->validate([
            "item_id" => 'required|integer',
        ]);
    }

    /**
     * Returns all baskets for a client
     *
     * @param Request $request
     * @return void
     */
    public function shopsBaskets(Request $request)
    {
        $data = $request->validate([
            /** TODO **/
            //            "user_id" => 'required|integer',
            // "description" => 'required|min:3',
            // "cif" => 'required|min:3',
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // // 'market_id' => ''
        ]);
        return ShopsBasketsResource::collection(Basket::where('user_id', $request->user())->get());
    }


    // public function confirm(Request $request)
    // {
    //     $basket = Basket::get($request->shop_id);
    //     if ($basket !== null) {
    //         $basket->status = Basket::STATUS_READY;
    //         $basket->save();
    //         return response()->noContent(); // 204
    //     } else {
    //         return abort(404);
    //     }
    // }
}
