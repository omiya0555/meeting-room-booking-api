<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'room_name' => '小会議室 A',
                'description' => '小規模の会議用ルームです。',
                'capacity' => 4, // 収容人数
            ],
            [
                'room_name' => '中会議室 B',
                'description' => '中規模の会議用ルームで、プレゼンテーションにも適しています。',
                'capacity' => 8, // 収容人数
            ],
            [
                'room_name' => '大会議室 C',
                'description' => '大規模な会議やセミナーに対応したルームです。',
                'capacity' => 20, // 収容人数
            ],
            [
                'room_name' => '特別会議室 D',
                'description' => '特別なイベントや会議用のルームです。',
                'capacity' => 12, // 収容人数
            ],
        ];

        // roomテーブルに挿入
        DB::table('rooms')->insert($rooms);
    }
}
