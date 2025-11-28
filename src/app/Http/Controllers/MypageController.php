<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\Profile;
use App\Models\Trade;
use App\Models\TradeReview;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile();
        $page = $request->input('page', 'sell');

        $sellingItems = Item::where('user_id', $user->id)->get();

        $purchasedItems = SoldItem::with('item')
            ->whereIn('item_id', function ($query) use ($user) {
                $query->select('id')->from('items')->where('user_id', $user->id);
            })
            ->get();

        $rating = TradeReview::where('reviewee_id', $user->id)->avg('rating');
        $rating = round($rating ?? 0);

        $trades = Trade::with(['item', 'messages', 'buyer', 'seller'])
            ->forUser($user->id)
            ->whereIn('status', ['chatting', 'completed'])
            ->get()
            ->sortByDesc(function ($trade) {
                // 最新メッセージの created_at を取得
                $lastMessageTime = $trade->messages->last()->created_at ?? null;

                return $lastMessageTime ?? $trade->updated_at;
            })
            ->values();



        foreach ($trades as $trade) {
            $trade->unreadCount = $trade->messages
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', 0)
                ->count();
        }

        $unreadTotal = $trades->sum('unreadCount');

        return view('purchase.show', compact(
            'user',
            'profile',
            'page',
            'sellingItems',
            'purchasedItems',
            'trades',
            'rating',
            'unreadTotal'
        ));
    }
}
