<?php

namespace Database\Seeders;

use App\Models\Basket;
use App\Models\Item;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds for Baskets/Items
     *
     * @return void
     */
    public function run()
    {
        // Load clients
        User::where('email', 'LIKE', 'client%')->each(function (User $user) {
            Product::all()->each(function (Product $product) use ($user) {
                if (random_int(0, 2) > 0) { // 66% of times
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

                    $unit = $product->units()->inRandomOrder()->first();

                    $item = new Item;
                    $item->product_id = $product->id;
                    $item->unit_id = $unit->id;
                    $item->quantity = random_int(1, 15);
                    $item->price = $unit->pivot->price;
                    $basket->items()->save($item);

                    if (random_int(0, 9) == 0) { // 10% of times
                        $basket->status = Basket::STATUS_CONFIRMED;
                        $basket->save();
                    }
                }
            });
        });
    }
}
