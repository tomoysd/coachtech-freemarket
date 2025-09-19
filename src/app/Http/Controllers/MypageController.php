<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page'); // buy / sell / null

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $purchased = $user->purchasesAsBuyer()->with('item')->latest('purchased_at')->paginate(10); // buyer
        $sold      = $user->purchasesAsSeller()->with('item')->latest('purchased_at')->paginate(10); // seller

        return view('mypage.index', compact('user', 'purchased', 'sold', 'page'));
    }

    // 任意エイリアス（/mypage?page=xxx が主仕様）
    public function buy(Request $request)
    {
        $request->merge(['page' => 'buy']);
        return $this->index($request);
    }
    public function sell(Request $request)
    {
        $request->merge(['page' => 'sell']);
        return $this->index($request);
    }
}
