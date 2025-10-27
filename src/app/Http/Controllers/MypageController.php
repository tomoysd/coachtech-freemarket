<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $user->load('profile');

        // 出品した商品：items.user_id = 自分
        $listedItems = $user->items()
            ->latest() // created_at desc
            ->paginate(12, ['*'], 'listed_page'); // ←ページャ名を分ける

        // 購入した商品：purchases.user_id = 自分 → item を辿る
        $purchased = $user->purchases()
            ->with('item')
            ->latest('created_at') // 購入は購入日時でOK
            ->paginate(12, ['*'], 'purchased_page'); // ←こちらも別名

        return view('profile.index', compact('user', 'listedItems', 'purchased'));
    }
}
