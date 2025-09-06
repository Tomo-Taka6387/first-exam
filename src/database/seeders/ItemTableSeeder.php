<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'condition_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'item_images/armani_watch.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'item_images/hdd.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 3,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'item_images/onion.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 4,
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'item_images/shose.png',
            ],
            [
                'user_id' => 1,
                'condition_id' => 1,
                'name' => 'ノートパソコン',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'img_url' => 'item_images/laptop.png',
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'item_images/mike.jpg',
            ],
            [
                'user_id' => 1,
                'condition_id' => 3,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'item_images/bag.png',
            ],
            [
                'user_id' => 1,
                'condition_id' => 4,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'img_url' => 'item_images/tumbler.png',
            ],
            [
                'user_id' => 1,
                'condition_id' => 1,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'img_url' => 'item_images/coffeemil.png',
            ],
            [
                'user_id' => 1,
                'condition_id' => 2,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'img_url' => 'item_images/makeset.png',
            ],
        ];
        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
