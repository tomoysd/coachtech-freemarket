<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    public const CONDITIONS = [
        '良好',
        '目立った傷や汚れなし',
        'やや傷や汚れあり',
        '状態が悪い',
    ];


    protected $fillable = [
        'user_id',
        'title',
        'brand',
        'description',
        'price',
        'condition',
        'image_url'
    ];

    protected $appends = ['image_url']; // 自動で JSON にも載る（任意）

    public function getImageUrlAttribute()
    {
        // 1) すでにフルURLが入っていればそのまま返す
        $url = $this->attributes['image_url'] ?? null;
        if ($url && Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function favoredBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいね（中間：favorites）
    public function likes()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories')
            ->using(ItemCategory::class) // 中間モデルを利用
            ->withTimestamps();
    }
}
