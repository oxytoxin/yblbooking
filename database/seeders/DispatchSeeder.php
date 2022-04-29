<?php

namespace Database\Seeders;

use App\Models\BusUnit;
use App\Models\Dispatch;
use App\Models\DispatchRoute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DispatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dispatch::factory()->count(100)->create();
    }
}
