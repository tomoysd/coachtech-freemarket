<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('users')->where('email','seller@example.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'seller',
                'email' => 'seller@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // ログインテスト用
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
