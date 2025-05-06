<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => '5oKqI@example.com',
                'password' => bcrypt('rifki123')
            ],
            [
                'name' => 'petugas',
                'email' => '9Y6k9@example.com',
                'password' => bcrypt('TUSMKN2BKL')
            ]
        ]);
    }
}
