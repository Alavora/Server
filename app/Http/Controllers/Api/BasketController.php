<?php

/**
 * Contoller class for Basket, and it's items. It allow users to create baskets, adding items and updating them.
 * It also allow sellers to get their baskets and update their items.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasketIndexResource;
use App\Http\Resources\BasketResource;
use App\Http\Resources\BasketShowResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ShopsBasketsItemsResource;
use App\Http\Resources\ShopsBasketsResource;
use App\Http\Resources\UnitResource;
use App\Models\Basket;
use App\Models\Item;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
        $user = Auth::user();
        $data = $request->validate([
            /** TODO **/
            'shop_id' => 'required|integer',
            // 'description' => 'required|min:3',
            // 'cif' => 'required|min:3',
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            // // 'market_id' => ''
        ]);
        DB::enableQueryLog();
        $basketList = BasketIndexResource::collection(Basket::where([['shop_id', $data['shop_id']], ['user_id', $user->id]])->get());
        dd(DB::getQueryLog());
        return $basketList;
    }

    /**
     * Returns a list of baskets for the current seller
     *
     * @param Request $request
     * @return BasketIndexResource
     */
    public function indexSeller(Request $request)
    {
        $user = Auth::user();
        // DB::enableQueryLog();
        $baskets = Basket::where('status', '=', Basket::STATUS_CONFIRMED)->whereHas(
            'shop.sellers',
            function (Builder $query) use ($user) {
                $query->where('owner_id', $user->id);
            }

        )->get();
        // dd(DB::getQueryLog());
        return BasketIndexResource::collection($baskets);
    }

    /**
     * Returns a basket (if seller has access to it)
     *
     * @param Request $request
     * @return BasketResource
     */
    public function getBasketSeller($basket_id)
    {
        $user = Auth::user();
        // DB::enableQueryLog();
        $basket = Basket::where([['status', '=', Basket::STATUS_CONFIRMED], ['id', $basket_id]])->whereHas(
            'shop.sellers',
            function (Builder $query) use ($user) {
                $query->where('owner_id', $user->id);
            }

        )->firstOrFail();
        // dd(DB::getQueryLog());
        return new BasketResource($basket);
    }

    /**
     * Allow the seller to update basket status
     *
     * @param Request $request
     * @return BasketResource
     */
    public function updateBasketSeller(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'status' => 'required|integer',
        ]);

        // DB::enableQueryLog();
        //where('status', '=', Basket::STATUS_CONFIRMED)->
        $basket = Basket::whereHas(
            'shop.sellers',
            function (Builder $query) use ($user) {
                $query->where('owner_id', $user->id);
            }

        )->firstOrFail();

        $basket->status = $data['status'];
        $basket->save();
        // dd(DB::getQueryLog());
        return new BasketResource($basket);
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
            /** TODO **/
            // 'name' => 'required|min:3',
            // 'description' => 'required|min:3',
            // 'cif' => 'required|min:3',
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
        $user = Auth::user();
        $values = $request->validate([
            'quantity' => 'required|numeric',
            'product_id' => 'required|integer',
            'unit_id' => 'required|integer',
        ]);
        $product = Product::findOrFail($values['product_id']);

        $unit = $product->units()->findOrFail($values['unit_id']);
        // DB::enableQueryLog();

        $basket = Basket::where([
            ['shop_id', $product->shop_id],
            ['user_id', $user->id],
            ['status', Basket::STATUS_UNCONFIRMED],
            ['user_id', $user->id]
        ])->first();

        if ($basket == null) {
            $data = [
                'shop_id' => $product->shop_id,
                'status' => Basket::STATUS_UNCONFIRMED,
                'user_id' => $user->id
            ];
            $basket = Basket::make($data);
            $basket->save();
        }
        // dd(DB::getQueryLog());


        $item = Item::where([
            'product_id' => $values['product_id'],
            'basket_id' => $basket->id,
        ])->first();
        if ($values['quantity'] == 0) {
            if (!empty($item)) {
                $item->delete();
            }
        } else {
            if (empty($item)) {
                $item = new Item;
                $item->product_id = $product->id;
                $item->unit_id = $unit->id;
                $item->quantity = $values['quantity'];
                $item->price = $unit->pivot->price;
            } else {
                $item->unit_id = $unit->id;
                $item->quantity = $values['quantity'];
                $item->price = $unit->pivot->price;
            }
            $basket->items()->save($item);
        }
    }

    /**
     * Returns the comment for a user/shop. This comment will belong to an unconfirmed basket. If the user has
     * no unconfirmed basket for the shop, the basket will be created with an empty message.
     *
     * @param Request $request
     * @return response { data: 'Comment text of unconfirmed user's basket for a shop' }
     */
    public function getComment(Request $request)
    {
        $user = Auth::user();
        $values = $request->validate([
            'shop_id' => 'required|integer',
        ]);

        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $values['shop_id'],
            'user_id' => $user->id,
        ]);

        return response()->json([
            'data' => $basket->comments
        ]);
    }

    /**
     * Replaces the comment in an unconfirmed basket for a user. If the user has no unconfirmed basket for the
     * shop, it will be created and the message attatched to it.
     *
     * @param Request $request
     * @return void
     */
    public function postComment(Request $request)
    {
        $user = Auth::user();
        $values = $request->validate([
            'comments' => 'required',
            'shop_id' => 'required|integer',
        ]);
        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $request->shop_id,
            'user_id' => $user->id,
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
        $user = Auth::user();
        $values = $request->validate([
            'shop_id' => 'required|integer',
        ]);
        $basket = Basket::where('status', Basket::STATUS_UNCONFIRMED)->firstOrCreate([
            'shop_id' => $values['shop_id'],
            'user_id' => $user->id,
        ]);
        $basket->status = Basket::STATUS_CONFIRMED;
        $basket->save();
        return response()->json([
            'successful' => true,
        ]);
    }

    public function itemConfirm(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|integer',
        ]);
        $item = Item::find($data['item_id']);

        $item->status = Item::STATUS_CONFIRMED;
        $item->save();
        return response()->json([
            'successful' => true,
        ]);
    }

    public function itemUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'item_id' => 'required|unique:items',
            'quantity' => 'required|numeric',
            'product_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'status' => 'integer',
        ]);

        //check if user has access to item
        $item = Basket::whereHas(
            'product.shop.sellers',
            function (Builder $query) use ($user) {
                $query->where('user_id', $user->id);
            }

        )->firstOrFail();
        $unit = Unit::findOrFail($data['unit_id']);
        $item->unit_id = $unit->id;
        $item->quantity = $data['quantity'];
        $item->price = $unit->price;
        $item->save();
    }

    /**
     * Returns all baskets for a client
     *
     * @param Request $request
     * @return void
     */
    public function shopsBaskets(Request $request)
    {
        $user = Auth::user();
        return ShopsBasketsResource::collection(Basket::where('user_id', $user->id)->get());
    }

    /**
     * Returns items of a basket, if current user is a seller and has access to them
     *
     * @param Request $request
     * @param integer $basket_id
     * @return Collection
     */
    public function itemsSeller(Request $request, int $basket_id)
    {
        $user = Auth::user();
        // DB::enableQueryLog();
        $basket = Basket::where([['status', '=', Basket::STATUS_CONFIRMED], ['id', $basket_id]])->first();
        if ($basket->shop->sellers->firstWhere('id', $user->id) == null) {
            return response()->json(['status_message' => 'Unauthorised'], 401);
        }
        $items = $basket->items;
        // dd(DB::getQueryLog());
        return ItemResource::collection($items);
    }
}
