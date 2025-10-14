<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['name','sort_order'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
