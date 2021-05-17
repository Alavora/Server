<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name()."'s Shop",
            'cif' => $this->faker->unique()->vat(),
            'phone' =>$this->faker->unique()->phoneNumber(),
            'address' =>$this->faker->unique()->address(),
            'longitude' =>$this->faker->unique()->longitude(),
            'latitude' =>$this->faker->unique()->latitude()
        ];
    }
}