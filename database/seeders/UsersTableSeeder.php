<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ユーザーのデータを配列で作成
        $users = [
            [
                'name' => 'user1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'user2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'testuser',
                'email' => 'test@gmail.com',
                'password' => Hash::make('testuser'),
            ],
        ];

        // ユーザーデータを挿入
        DB::table('users')->insert($users);
    }
}

