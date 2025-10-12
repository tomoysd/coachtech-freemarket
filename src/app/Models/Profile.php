<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        $path = $this->avatar_path ?? null;
        if (!$path) return null;

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . $path);
    }

    /**
     * Userとのリレーション（1対1）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
