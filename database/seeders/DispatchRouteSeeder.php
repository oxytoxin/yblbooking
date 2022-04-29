<?php

namespace Database\Seeders;

use App\Models\DispatchRoute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DispatchRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DispatchRoute::create([
            'from_terminal' => 1,
            'to_terminal' => 2,
            'distance_in_km' => 38,
            'fare' => 132,
        ]);
        DispatchRoute::create([
            'from_terminal' => 1,
            'to_terminal' => 3,
            'distance_in_km' => 42,
            'fare' => 105,
        ]);
        DispatchRoute::create([
            'from_terminal' => 2,
            'to_terminal' => 1,
            'distance_in_km' => 29,
            'fare' => 132,
        ]);
        DispatchRoute::create([
            'from_terminal' => 2,
            'to_terminal' => 3,
            'distance_in_km' => 75,
            'fare' => 152,
        ]);
        DispatchRoute::create([
            'from_terminal' => 3,
            'to_terminal' => 1,
            'distance_in_km' => 47,
            'fare' => 105,
        ]);
        DispatchRoute::create([
            'from_terminal' => 3,
            'to_terminal' => 2,
            'distance_in_km' => 26,
            'fare' => 152,
        ]);
    }
}
