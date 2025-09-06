<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\profile;

class MypageController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        $profile = $user->profile ?? new profile();
        $page = $request->input('page', 'sell');

        $sellingItems = Item::where('user_id', $user->id)->get();
        $purchasedItems = SoldItem::with('item')
            ->whereIn('item_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('items')
                    ->where('user_id', $user->id);
            })
            ->get();

        $likedItems = $user->likes()
            ->with('item.soldItem')
            ->get()
            ->map(fn($like) => $like->item)
            ->filter(fn($item) => $item && $item->user_id !== $user->id)
            ->values();

        return view('purchase.show', compact('user', 'profile', 'page', 'sellingItems', 'purchasedItems', 'likedItems'));
    }
}
