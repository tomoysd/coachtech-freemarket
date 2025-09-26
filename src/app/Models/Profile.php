<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar_path',
        'postal_code',
        'prefecture',
        'address1',
        'address2',
        'phone',
    ];

    /**
     * Userとのリレーション（1対1）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
