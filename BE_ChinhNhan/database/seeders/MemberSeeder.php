<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            'username'=> 'member_test',
            'user_id'           => 'ID1001', // varchar(250)
            'mem_code'          => 'MEM888', // varchar(150)
            'email'             => 'member@gmail.com', // varchar(200)
            'password'          => Hash::make('12345678'), // varchar(100)
            'address'           => '123 Đường ABC, Phường 5, Quận 3', // varchar(200)
            'company'           => 'Công ty TNHH Giải Pháp Số', // varchar(250)
            'full_name'         => 'Nguyễn Văn Member', // varchar(250)
            'provider'          => 'credentials', // varchar(255)
            'provider_id'       => null, // varchar(255)
            'avatar'            => 'default.png', // varchar(255)
            'phone'             => '0987654321', // varchar(50)
            'gender'            => 'Nam', // varchar(250)
            'dateOfBirth'       => '1995-10-20', // varchar(500)
            'Tencongty'         => 'Digital Solution Co.', // varchar(250)
            'Masothue'          => '0102030405', // varchar(250)
            'Diachicongty'      => 'TP. Hồ Chí Minh, Việt Nam', // varchar(250)
            'Sdtcongty'         => '02838000000', // varchar(250)
            'emailcty'          => 'office@digital.com', // varchar(250)
            'MaKH'              => 'KH_999', // varchar(250)
            'district'          => 'Quận 3', // varchar(200)
            'ward'              => 'Phường 5', // varchar(200)
            'city_province'     => 'Hồ Chí Minh', // varchar(200)
            'status'            => 1, // int(11)
            'm_status'          => 1, // int(11)
            'accumulatedPoints' => 500, // int(255)
            'date_join'         => Carbon::now()->format('Y-m-d H:i:s'), // varchar(200)
            'password_token'    => null, // varchar(255)
            'created_at'        => now(), // timestamp
            'updated_at'        => now(), // timestamp

        ]);
    }
}
