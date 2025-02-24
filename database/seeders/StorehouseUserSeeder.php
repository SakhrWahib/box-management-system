<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StorehouseUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('storehouse_users')->insert([
            'name' => 'مستخدم المستودع',
            'email' => 'storehouse@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
} 