<?php

namespace Database\Seeders;

use App\Models\Market;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Market::all()->each(function (Market $market) {
            $shops = collect([]);
            for ($i = 0; $i < random_int(3, 20); $i++) {
                $shop= Shop::factory()->make();

                $shops->push($shop);
                
            }
            // dd($shops);
            $market->shops()->saveMany($shops);
        });
        

        //Shop::factory()->count(5)->create();
    }
}