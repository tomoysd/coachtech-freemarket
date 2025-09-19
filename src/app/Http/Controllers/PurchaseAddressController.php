<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class PurchaseAddressController extends Controller
{
    /**
     * 住所変更フォーム（購入フロー内）
     * 仕様表が item_id だったので Purchase は item から引く想定
     */
    public function edit(Item $item)
    {
        $purchase = Purchase::where('item_id', $item->id)
            ->where('buyer_id', auth()->id())
            ->latest()->first();

        $address = $purchase?->shippingAddress; // hasOne

        return view('purchase.address_edit', compact('item','purchase','address'));
    }

    /**
     * 住所更新
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'recipient_name' => ['required','string','max:50'],
            'postal_code'    => ['required','regex:/^\d{3}-\d{4}$/'],
            'prefecture'     => ['required','string','max:20'],
            'city'           => ['required','string','max:50'],
            'address_line1'  => ['required','string','max:100'],
            'address_line2'  => ['nullable','string','max:100'],
            'phone'          => ['nullable','string','max:20'],
        ]);

        $purchase = Purchase::where('item_id', $item->id)
            ->where('buyer_id', auth()->id())
            ->latest()->firstOrFail();

        $purchase->shippingAddress()->updateOrCreate(
            ['purchase_id' => $purchase->id],
            $validated
        );

        return back()->with('message','配送先を更新しました');
    }
}
