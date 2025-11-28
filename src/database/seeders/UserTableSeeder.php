<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => '1',
            'name' => 'テスト 太郎',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'id' => '2',
            'name' => 'テスト 次郎',
            'email' => 'jiro@test.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'id' => '3',
            'name' => 'テスト 三郎',
            'email' => 'saburo@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}
