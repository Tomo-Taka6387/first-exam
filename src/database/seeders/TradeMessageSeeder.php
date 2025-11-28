<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TradeMessage;
use App\Models\Trade;

class TradeMessageSeeder extends Seeder
{
    public function run()
    {
        $trades = Trade::all();

        foreach ($trades as $trade) {
            TradeMessage::create([
                'trade_id'   => $trade->id,
                'sender_id'  => $trade->seller_id,
                'message'    => "取引メッセージを送ります。",
                'is_read'    => 0,
            ]);
        }
    }
}
