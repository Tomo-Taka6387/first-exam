<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trade;

class TradeSeeder extends Seeder
{
    public function run()
    {
        // user1 の商品（1〜5）を user2 が購入
        for ($i = 1; $i <= 5; $i++) {
            Trade::create([
                'item_id' => $i,
                'seller_id' => 1,
                'buyer_id' => 2,
                'status' => 'chatting',
            ]);
        }

        // user2 の商品（6〜10）を user1 が購入
        for ($i = 6; $i <= 10; $i++) {
            Trade::create([
                'item_id' => $i,
                'seller_id' => 2,
                'buyer_id' => 1,
                'status' => 'chatting',
            ]);
        }
    }
}
