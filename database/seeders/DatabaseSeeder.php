<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seederを呼び出す
        $this->call(UsersTableSeeder::class);
        $this->call(BookingStatusSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(RoomEquipmentSeeder::class);
    }
}
