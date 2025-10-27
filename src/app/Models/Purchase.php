<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
    ];

    public const METHOD_MAP = [
        1 => 'コンビニ支払い',
        2 => 'カード支払い'
    ];
    public function getPaymentMethodLabelAttribute(): string
    {
        return self::METHOD_MAP[$this->payment_method] ?? '不明';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }
}
