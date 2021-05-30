<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = User::factory()->make();
            $user->email = 'seller' . $i . '@example.com';
            $user->save();
        }
        for ($i = 1; $i <= 30; $i++) {
            $user = User::factory()->make();
            $user->email = 'client' . $i . '@example.com';
            $user->save();
        }
    }
}
