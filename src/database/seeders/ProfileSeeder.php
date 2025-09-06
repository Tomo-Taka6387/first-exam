<?php

namespace Database\Seeders;

use App\Models\Profile;

use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::create([
            'user_id' => 1,
            'img_url' => 'profile_images/kiwi.png',
            'postcode' => '123-4567',
            'address' => '東京都新宿区1-2-3',
            'building' => 'ビルディング123',
        ]);
    }
}
