<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'brand',
        'description',
        'price',
        'condition',
        'image_url'
    ];

    // 商品状態の定数リスト
    const CONDITIONS = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い',
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

    public function getConditionLabelAttribute()
    {
        return self::CONDITIONS[$this->condition] ?? '不明';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
// 商品が「購入」されたか判定に使う（1商品1購入想定でも hasMany の方が集計しやすい）
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
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
