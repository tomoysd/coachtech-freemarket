<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;

class ShippingAddressController extends Controller
{
    // 住所変更フォーム（購入前なのでセッションに保持）
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

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

        return view('purchase.address.edit', [
            'item'    => $item,
            'address' => (object)$addr,
        ]);
    }

    // 住所変更の反映（セッション保存 → 購入画面へ戻る）
    public function update(AddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        // 住所入力画面では name を受け取らないため、ここで補完する
        $validated['name'] = Auth::user()->name ?? '';



        session(["shipping_address.$item_id" => $validated]);

        return redirect()
            ->route('purchase.create', ['item_id' => $item_id])
            ->with('message', '配送先住所を更新しました。');
    }
}
