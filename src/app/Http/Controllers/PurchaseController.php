<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面
     */
    public function create(Item $item)
    {
        // 既に売却済みなら弾く
        if ($item->status === 'sold') {
            return redirect()->route('items.show', $item)->with('message','この商品は売り切れです');
        }

        return view('purchase.create', [
            'item' => $item,
            // 配送先・支払い方法の既定値などを渡すならここで
        ]);
    }

    /**
     * 購入処理
     */
    public function store(Request $request, Item $item)
    {
        $request->validate([
            // ここに支払い/配送のバリデーション
        ]);

        if ($item->status === 'sold') {
            return back()->with('message','この商品は売り切れです');
        }

        // 1商品1取引にするためトランザクションで確定
        return DB::transaction(function () use ($item) {
            $purchase = Purchase::create([
                'item_id'         => $item->id,
                'buyer_id'        => auth()->id(),
                'seller_id'       => $item->user_id,
                'amount'          => $item->price,
                'payment_status'  => 'paid',
                'shipping_status' => 'pending',
                'purchased_at'    => now(),
            ]);

            $item->update([
                'status'  => 'sold',
                'sold_at' => now(),
            ]);

            return redirect()->route('mypage')->with('message','購入が完了しました');
        });
    }
}
