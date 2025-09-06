<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategoryTableSeeder::class,
            ConditionTableSeeder::class,
            UserTableSeeder::class,
            ProfileSeeder::class,
            ItemTableSeeder::class,
            LikeTableSeeder::class,
        ]);
    }
}
