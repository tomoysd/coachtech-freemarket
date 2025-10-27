<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    // 購入画面
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 支払い方法（GETで反映：?payment_method=...）※JSなし対応
        $paymentMethod = $request->query('payment_method');

        // 住所：セッションに一時保存があればそれを表示。なければプロフィールから初期値。
        $sessionKey = "shipping_address.$item_id";
        $addr = session($sessionKey);
        if (!$addr) {
            $p = $user->profile; // プロフィール前提（postal_code/prefecture/address1/address2/phone）
            $addr = [
                'name' => $user->name ?? '',
                'postal_code'    => $p->postal_code ?? '',
                'address'       => $p->address ?? '',
                'building'       => $p->building ?? '',
            ];
        }

        $shipping = (object)$addr;

        return view('purchase.create', [
            'item'           => $item,
            'shipping'      => $shipping,
            'paymentMethod'  => $paymentMethod,
        ]);
    }

    // 購入確定
    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // 既に誰かが購入していないか（1件でもあれば sold）
        $already = Purchase::where('item_id', $item->id)->exists();
        if ($already) {
            return redirect('/')->with('error', 'この商品はすでに購入されています。');
        }

        // 住所（セッション → なければプロフィール）
        $sessionKey = "shipping_address.$item_id";
        $addr = session($sessionKey);
        if (!$addr) {
            $p = $user->profile;
            $addr = [
                'name' => $user->name ?? '',
                'postal_code'    => $p->postal_code ?? '',
                'address'       => $p->address ?? '',
                'building'       => $p->building ?? '',
            ];
        }

        // 最終ガード：セッションに name が無いケースをユーザー名で補完
        $addr = array_merge(['name' => $user->name ?? ''], $addr);


        DB::transaction(function () use ($request, $user, $item, $addr) {
            // purchases 登録（仕様書カラムに一致）
            $purchase = Purchase::create([
                'user_id'      => $user->id,
                'item_id'      => $item->id,
                'payment_method' => (int)$request->payment_method,
            ]);

            // shipping_addresses 登録（purchase_idで紐づく）
            ShippingAddress::create(array_merge($addr, [
                'purchase_id' => $purchase->id,
            ]));
        });

        // 住所の一時データは破棄
        session()->forget($sessionKey);

        // 遷移先：商品一覧（要件2-4）
        return redirect('/')->with('message', '購入が完了しました。');
    }
}
