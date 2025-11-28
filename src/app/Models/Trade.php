<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = ['item_id', 'seller_id', 'buyer_id', 'status'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('seller_id', $userId)->orWhere('buyer_id', $userId);
        });
    }

    public function getUnreadCountAttribute()
    {
        $userId = auth()->id();
        return $this->messages
            ->where('sender_id', '!=', $userId)
            ->where('is_read', 0)
            ->count();
    }

    public function partner($userId)
    {
        return $this->seller_id === $userId ? $this->buyer : $this->seller;
    }


    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function messages()
    {
        return $this->hasMany(TradeMessage::class)->orderBy('created_at');
    }
}
