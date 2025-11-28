<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\TradeReview;
use Illuminate\Http\Request;

class TradeReviewController extends Controller
{
    public function store(Request $request, $tradeId)
    {
        $validated = $request->validate([
            'rating' => 'nullable|integer|min:0|max:5',
            'reviewee_id' => 'required|integer',
        ]);

        $trade = Trade::findOrFail($tradeId);

        TradeReview::create([
            'trade_id' => $tradeId,
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $validated['reviewee_id'],
            'rating' => $validated['rating'] ?? 0,
        ]);

        $trade = $trade->fresh();


        $reviewCount = TradeReview::where('trade_id', $tradeId)->count();

        if ($reviewCount >= 2) {
            $trade->update(['status' => 'finished']);
        }

        return redirect('/');
    }
}
