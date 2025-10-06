<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

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
                'recipient_name' => $user->name ?? '',
                'postal_code'    => $p->postal_code ?? '',
                'prefecture'     => $p->prefecture ?? '',
                'address1'       => $p->address1 ?? '',
                'address2'       => $p->address2 ?? '',
                'phone'          => $p->phone ?? '',
            ];
        }

        return view('shipping.edit', [
            'item'    => $item,
            'address' => (object)$addr,
        ]);
    }

    // 住所変更の反映（セッション保存 → 購入画面へ戻る）
    public function update(Request $request, $item_id)
    {
        $validated = $request->validate([
            'recipient_name' => ['required','string','max:255'],
            'postal_code'    => ['required','string','max:10'],
            'prefecture'     => ['required','string','max:50'],
            'address1'       => ['required','string','max:255'],
            'address2'       => ['nullable','string','max:255'],
            'phone'          => ['nullable','string','max:20'],
        ]);

        session(["shipping_address.$item_id" => $validated]);

        return redirect()->route('purchase.create', ['item_id' => $item_id])
            ->with('message', '配送先住所を更新しました。');
    }
}
