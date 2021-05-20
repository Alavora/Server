<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demo_units = [
            'kg' => 'Quilos',
            'gr' => 'Grams',
            'lt' => 'Litres',
            'cl' => 'Centilítrs',
            'un' => 'Unitats',
            'dtz' => 'Dotzena',
        ];
        foreach ($demo_units as $symbol => $name) {
            $unit = Unit::factory()->make();
            $unit->symbol = $symbol;
            $unit->name = $name;
            $unit->save();
        }
    }
}
