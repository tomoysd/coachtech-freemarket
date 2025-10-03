<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle($item_id)
    {
        $item = \App\Models\Item::findOrFail($item_id);
        $user = auth()->user();

        // favorites（user_id, item_id のユニーク）想定
        // 既存のテーブル名が違う場合はリレーション側で合わせてね（下のモデル参照）
        if ($item->likes()->where('user_id', $user->id)->exists()) {
            $item->likes()->detach($user->id);
        } else {
            $item->likes()->attach($user->id);
        }

        return back();
    }
}
