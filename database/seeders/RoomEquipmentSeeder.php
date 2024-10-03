<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomEquipmentSeeder extends Seeder
{
    public function run()
    {
        // 備品データ
        $equipments = [
            // 小会議室 A
            ['room_id' => 1, 'equipment_name' => 'WiFi'], 
            ['room_id' => 1, 'equipment_name' => 'ホワイトボード'], 
            // 中会議室 B
            ['room_id' => 2, 'equipment_name' => 'WiFi'], 
            ['room_id' => 2, 'equipment_name' => 'ホワイトボード'], 
            ['room_id' => 2, 'equipment_name' => '黒マーカー'],
            // 大会議室 C 
            ['room_id' => 3, 'equipment_name' => 'WiFi'], 
            ['room_id' => 3, 'equipment_name' => 'ホワイトボード'], 
            ['room_id' => 3, 'equipment_name' => '黒マーカー'], 
            ['room_id' => 3, 'equipment_name' => '机'], 
            ['room_id' => 3, 'equipment_name' => 'いす'], 
            // 特別会議室 D
            ['room_id' => 4, 'equipment_name' => 'WiFi'], 
            ['room_id' => 4, 'equipment_name' => 'ホワイトボード'], 
            ['room_id' => 4, 'equipment_name' => '机'], 
            ['room_id' => 4, 'equipment_name' => 'いす'], 
        ];

        // room_equipmentsテーブルに挿入
        DB::table('room_equipments')->insert($equipments);
    }
}