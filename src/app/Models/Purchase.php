<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    public function item():BelongsTo
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
    public function buyer():BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'buyer_id');
    }
    public function seller():BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'seller_id');
    }
    public function shippingAddress():HasOne
    {
        return $this->hasOne(\App\Models\ShippingAddress::class);
    }
}
