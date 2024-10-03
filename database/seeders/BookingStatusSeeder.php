<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['status_name' => '承認待ち'], // Pending
            ['status_name' => 'キャンセル'], // Canceled
            ['status_name' => '承認済み'], // Approved
            ['status_name' => '使用中'], // In Use
            ['status_name' => '終了'], // Completed
        ];

        DB::table('booking_statuses')->insert($statuses);
    }
}
