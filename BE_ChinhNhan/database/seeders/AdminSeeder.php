<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            'id'=>'202',
            'type'         => '1', // 1: Main Admin
            'username'     => 'admin_khang',
            'password'     => Hash::make('admin123'),
            'email'        => 'admin@bepviet.vn',
            'display_name' => 'Quản Trị Viên Hệ Thống',
            'avatar'       => null,
            'skin'         => 'blue', 
            'depart_id'    => 1,
            'is_default'   => 1,
            'lastlogin'    => '0',
            'status'       => 1, 
            'phone'        => '0987654321',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        DB::table('admin')->insert([
            'id'=>'203',
            'type'         => '1', // 1: Main Admin
            'username'     => 'admin_duy',
            'password'     => Hash::make('admin123'),
            'email'        => 'levuvinhkhang',
            'display_name' => 'Quản Trị Viên Hệ Thống',
            'avatar'       => null,
            'skin'         => 'blue', 
            'depart_id'    => 1,
            'is_default'   => 1,
            'lastlogin'    => '0',
            'status'       => 1, 
            'phone'        => '0987654321',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
