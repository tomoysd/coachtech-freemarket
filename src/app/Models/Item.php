<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','title','description','price',
        'condition_id','status','sold_at','image_path',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function condition(){ return $this->belongsTo(Condition::class); }
    public function purchase(){ return $this->hasOne(Purchase::class); }
}
