<?php

namespace App\Mail;

use App\Models\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $trade;

    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    public function build()
    {
        return $this->subject('【取引完了】商品が完了しました')
            ->view('emails.trade_completed');
    }
}
