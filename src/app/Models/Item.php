<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'condition_id',
        'status',
        'sold_at',
        'image_path',
    ];

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

    // 複数カテゴリ想定
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
