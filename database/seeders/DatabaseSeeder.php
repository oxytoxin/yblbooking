<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(DispatchTypeSeeder::class);
        $this->call(TerminalSeeder::class);
        $this->call(BusClassSeeder::class);
        $this->call(BusUnitSeeder::class);
        $this->call(DispatchRouteSeeder::class);
        $this->call(DispatchSeeder::class);
        User::create([
            'role_id' => Role::ADMIN,
            'name' => 'Adrian Calixtro',
            'email' => 'adriancalixtro@gmail.com',
            'address' => 'Koronadal City',
            'birthday' => Carbon::parse('December 20, 1999'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
        User::create([
            'role_id' => Role::PASSENGER,
            'name' => 'Walk-in Passenger',
            'email' => 'walkinpassenger@gmail.com',
            'address' => 'Koronadal City',
            'birthday' => today(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
        $this->call(BookingSeeder::class);
    }
}
