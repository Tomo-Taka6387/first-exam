<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'price',
        'brand',
        'description',
        'img_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function likeByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function soldUsers()
    {
        return $this->belongsToMany(User::class, 'sold_items');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function soldItem()
    {
        return $this->hasOne(SoldItem::class);
    }

    public function isSold()
    {
        return $this->soldItem()->exists();
    }
}
