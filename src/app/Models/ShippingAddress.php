<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'recipient_name',
        'postal_code',
        'prefecture',
        'address1',
        'address2',
        'phone',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
