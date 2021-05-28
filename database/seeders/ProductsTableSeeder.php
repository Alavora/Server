<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\UnitController;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    private function demo_products()
    {
        switch (random_int(0, 1)) {
            case 0:
                return [
                    'Pomes' => 'un,kg',
                    'Peres' => 'un,kg',
                    'Prunes' => 'un,kg',
                    'Plàtans' => 'un,kg',
                ];
            case 1:
                return [
                    'Pernil Salat' => 'gr,kg',
                    'Pintxos' => 'gr,un',
                    'Hamburgueses' => 'un',
                    'Butifarres' => 'un',
                    'Mandonguilles' => 'gr',
                    'Paté' => 'gr',
                    'Costelles de xai' => 'un,gr'
                ];
        }
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::all()->each(function (Shop $shop) {
            $products = collect([]);
            $demo_data = $this->demo_products();
            foreach ($demo_data as $name => $units) {
                if (random_int(0, 2) > 0) { // 66% of times
                    $product = Product::factory()->make();
                    $product->name = $name;
                    $product->price = random_int(1, 9999) / 100;
                    $product->shop_id = $shop->id;
                    $product->save();

                    $units = explode(',', $units);

                    $product_units = [];
                    foreach ($units as $unit) {
                        $u = Unit::Where('symbol', $unit)->first();
                        // dd($u);
                        $product_units[$u->id] = random_int(1, 9999) / 100;
                    }

                    $product->units()->sync($this->mapUnits($product_units));
                }
            }


            // dd($shops);
            $shop->products()->saveMany($products);
            // $shop->save();
        });
    }
    private function mapUnits($units)
    {
        // dd($units);
        return collect($units)->map(function ($i) {
            return ['price' => $i];
        });
    }
}
