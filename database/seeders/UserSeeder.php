<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Kadek Artika',
            'udomain' => 'u111111',
            'password' => Hash::make('password'),
            'phone' => '6283178723263'
        ]);

        User::create([
            'name' => 'Naufal Dimas',
            'udomain' => 'u222222',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Ivan Cornelius',
            'udomain' => 'u333333',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Hansen Gunawan',
            'udomain' => 'u444444',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Gandhi Winata',
            'udomain' => 'u555555',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Monica Agustina',
            'udomain' => 'u666666',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Grace Natal',
            'udomain' => 'u777777',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Natanael Yohanes',
            'udomain' => 'u888888',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Jason Amanda',
            'udomain' => 'u999999',
            'password' => Hash::make('password'),
        ]);
    }
}
