<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ChatRequest;
use App\Models\Trade;
use App\Models\TradeMessage;
use App\Models\TradeReview;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeCompleted;


class ChatController extends Controller
{
    public function show($tradeId)
    {
        $user = auth()->user();
        $trade = Trade::with(['item', 'buyer.profile', 'seller.profile', 'messages.sender.profile'])
            ->findOrFail($tradeId);


        if (!in_array($user->id, [$trade->buyer_id, $trade->seller_id])) {
            abort(403);
        }

        $partner = $trade->seller_id === $user->id ? $trade->buyer : $trade->seller;


        foreach ($trade->messages as $msg) {
            if ($msg->sender_id !== $user->id && $msg->is_read == 0) {
                $msg->update(['is_read' => 1]);
            }
        }


        $otherTrades = Trade::with(['item', 'messages'])
            ->where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->where('id', '!=', $trade->id)
            ->get()
            ->sortByDesc(fn($t) => optional($t->messages->last())->created_at);

        foreach ($otherTrades as $t) {
            $t->unreadCount = $t->messages
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', 0)
                ->count();
        }


        $shouldShowModal = false;


        if (session('show_review_modal')) {
            $shouldShowModal = true;
            session()->forget('show_review_modal');
        } elseif ($trade->status === 'completed' && $user->id !== $trade->buyer_id) {
            $alreadyReviewed = TradeReview::where('trade_id', $trade->id)
                ->where('reviewer_id', $user->id)
                ->exists();
            if (!$alreadyReviewed) {
                $shouldShowModal = true;
            }
        }

        $drafts = session('chat_drafts', []);
        $draft = $drafts[$tradeId] ?? null;


        return view('purchase.chat', [
            'user' => $user,
            'trade' => $trade,
            'item' => $trade->item,
            'partner' => $partner,
            'otherTrades' => $otherTrades,
            'shouldShowModal' => $shouldShowModal,
            'draft' => $draft,
        ]);
    }

    public function store(ChatRequest $request, $tradeId)
    {
        $trade = Trade::findOrFail($tradeId);
        $userId = auth()->id();

        $imgPath = $request->hasFile('img_url')
            ? $request->file('img_url')->store('chat_images', 'public')
            : null;

        TradeMessage::create([
            'trade_id' => $trade->id,
            'sender_id' => $userId,
            'message' => $request->message,
            'img_path' => $imgPath,
            'is_read' => 0,
        ]);

        $drafts = session('chat_drafts', []);
        unset($drafts[$tradeId]);
        session(['chat_drafts' => $drafts]);

        return redirect()->route('chat.show', $tradeId);
    }

    public function update(ChatRequest $request, TradeMessage $message)
    {
        if ($message->sender_id !== auth()->id()) abort(403);

        $imgPath = $request->hasFile('img_url')
            ? $request->file('img_url')->store('chat_images', 'public')
            : $message->img_path;

        $message->update([
            'message' => $request->message,
            'img_path' => $imgPath,
        ]);

        return redirect()->route('chat.show', $message->trade_id);
    }

    public function destroy(TradeMessage $message)
    {
        if ($message->sender_id !== auth()->id()) abort(403);

        $tradeId = $message->trade_id;
        $message->delete();

        return redirect()->route('chat.show', $tradeId);
    }

    public function complete($tradeId)
    {
        $trade = Trade::findOrFail($tradeId);
        $user = auth()->user();

        if (!in_array($user->id, [$trade->buyer_id, $trade->seller_id])) abort(403);

        $trade->update(['status' => 'completed']);

        if ($user->id === $trade->buyer_id) {
            Mail::to($trade->seller->email)->send(new TradeCompleted($trade));
            session()->flash('show_review_modal', true);
        }

        return redirect()->route('chat.show', $tradeId);
    }

    public function saveDraft(Request $request, $tradeId)
    {
        $drafts = session('chat_drafts', []);
        $drafts[$tradeId] = $request->message;

        session(['chat_drafts' => $drafts]);

        return response()->json(['status' => 'ok']);
    }
}
