<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     //user: Ngocanh@1811, admin: Natridn@1811
    public function run()
    {
        $faker = Faker::create();

        // Tạo dữ liệu giả cho bảng users
        for ($i = 0; $i < 20; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('Ngocanh@1811'),
                //'password' => Hash::make('Natridn@1811'),
                'role' => 'user',
            ]);
        }
    }
}
