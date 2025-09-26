<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\ShippingAddress; // ★ 追加：配送先モデルを利用
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面
     */
    public function create(Item $item)
    {
        // 既に売却済みなら戻す
        if ($item->status === 'sold') {
            return redirect()
                ->route('items.show', $item)
                ->with('message', 'この商品は売り切れです。');
        }

        return view('purchase.create', [
            'item' => $item,
            // ※必要ならプロフィールのデフォルト住所を画面に出す（任意）
            // 'defaultAddress' => optional(auth()->user()->profile),
        ]);
    }

    /**
     * 購入処理
     */
    public function store(Request $request, Item $item)
    {
        // ここに支払い/配送のバリデーション（必要に応じて）
        $request->validate([
            // 例） 'agree' => ['accepted'],
        ]);

        if ($item->status === 'sold') {
            return back()->with('message', 'この商品は売り切れです。');
        }

        // 1商品1取引にするためトランザクションで確定
        return DB::transaction(function () use ($item) {

            // ---- 1) purchases を作成（既存ロジック）----
            $purchase = Purchase::create([
                'item_id'         => $item->id,
                'buyer_id'        => auth()->id(),
                'seller_id'       => $item->user_id,
                'amount'          => $item->price,
                'payment_status'  => 'paid',
                'order_status'    => 'pending',
                'purchased_at'    => now(),
            ]);

            // ---- 2) プロフィールの住所をコピーして shipping_addresses を作成（★ 追加）----
            $user = auth()->user();
            $profile = $user->profile; // ユーザーのデフォルト住所

            // プロフィール未登録時はエラーにしたい場合は例外を投げる
            if (!$profile) {
                // ※要件に合わせてハンドリング（ここでは例外→ロールバック）
                throw new \RuntimeException('プロフィールに住所が登録されていません。');
            }

            ShippingAddress::create([
                'purchase_id'    => $purchase->id,
                'recipient_name' => $user->name,           // ユーザー名をコピー（編集不可）
                'postal_code'    => $profile->postal_code, // 以降、プロフィールの住所をコピー
                'prefecture'     => $profile->prefecture,
                'address1'       => $profile->address1,
                'address2'       => $profile->address2,
                'phone'          => $profile->phone,
            ]);

            // ---- 3) 商品を売却済みに更新（既存ロジック）----
            $item->update([
                'status'  => 'sold',
                'sold_at' => now(),
            ]);

            // ---- 4) 正常終了 ----
            return redirect()
                ->route('mypage')
                ->with('message', '購入が完了しました');
        });
    }
}
