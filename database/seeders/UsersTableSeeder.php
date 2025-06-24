<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'is_premium' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@customer.com',
                'is_premium' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'remember_token' => Str::random(10),
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'name' => 'Craftsperson',
                'email' => 'craftsperson@craftsperson.com',
                'is_premium' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'craftsperson',
                'remember_token' => Str::random(10),
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'name' => 'Md Mizanur Rahman',
                'email' => 'mr7517218@gmail.com',
                'is_premium' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'customer',
                'remember_token' => Str::random(10),
                'status' => 'active',
                'created_at' => now(),
            ],
        ]);
    }
}
