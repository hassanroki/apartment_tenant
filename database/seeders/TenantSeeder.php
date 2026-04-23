<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tenants')->insert([
            [
                'name' => 'Tania Akter',
                'email' => 'rhassan5550@gmail.com',
                'phone' => '01710000001',
                'password' => Hash::make('password123'),
                'img' => null,
            ],
            [
                'name' => 'Mahmudul Hasan',
                'email' => 'mdrokibulhassan786@gmail.com',
                'phone' => '01710000002',
                'password' => Hash::make('password123'),
                'img' => null,
            ],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'hassan0cse@gmail.com',
                'phone' => '01710000003',
                'password' => Hash::make('password123'),
                'img' => null,
            ],
            [
                'name' => 'Sabbir Ahmed',
                'email' => 'baits0hassan@gmail.com',
                'phone' => '01710000004',
                'password' => Hash::make('password123'),
                'img' => null,
            ],
            [
                'name' => 'Farhana Rahman',
                'email' => 'farhana.rahman@gmail.com',
                'phone' => '01710000005',
                'password' => Hash::make('password123'),
                'img' => null,
            ],

        ]);
    }
}
