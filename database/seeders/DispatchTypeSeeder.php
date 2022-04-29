<?php

namespace Database\Seeders;

use App\Models\Dispatch;
use App\Models\DispatchType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DispatchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DispatchType::create([
            'name' => 'nonstop',
            'stops' => 0,
        ]);
        DispatchType::create([
            'name' => '1-stop',
            'stops' => 1,
        ]);
        DispatchType::create([
            'name' => '2-stop',
            'stops' => 2,
        ]);
    }
}
