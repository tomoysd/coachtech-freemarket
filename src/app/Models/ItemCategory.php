<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemCategory extends Pivot
{
    use HasFactory;

    protected $table = 'item_categories';

    protected $fillable = [
        'item_id',
        'category_id',
    ];

    // 関連定義（任意。中間モデルからも辿れるように）
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
