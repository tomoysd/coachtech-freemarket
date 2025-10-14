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
        'condition_id',
        'category_id',
        'image_path'
    ];

    protected $appends = ['image_url']; // 自動で JSON にも載る（任意）

    public function getImageUrlAttribute()
    {
        // 1) すでにフルURLが入っていればそのまま返す
        $url = $this->attributes['image_url'] ?? null;
        if ($url && Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        // 2) 相対パス（storage/app/public 配下）なら公開URLに変換
        $path = $this->attributes['image_path'] ?? null;
        if ($path) {
            return asset('storage/'.$path);
        }

        // どちらも無ければ null
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
