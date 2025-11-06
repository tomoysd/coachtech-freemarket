<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = config('services.stripe.webhook_secret'); // services.php に追記します

        try {
            $event = Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature verify failed: ' . $e->getMessage());
            return response('invalid', 400);
        }

        switch ($event->type) {
            // カード＝ほぼこれ、コンビニ＝入金完了時にこれが飛ぶ
            case 'payment_intent.succeeded':
                $pi = $event->data->object; // \Stripe\PaymentIntent
                $itemId = $pi->metadata->item_id ?? null;
                $userId = $pi->metadata->user_id ?? null;
                $amount = $pi->amount_received ?? $pi->amount; // 円（最小単位）

                if ($itemId && $userId) {
                    // すでに購入処理済みならスキップ
                    if (Purchase::where('stripe_payment_intent_id', $pi->id)->exists()) {
                        return response('ok', 200);
                    }
                    // 在庫確定＆購入レコード作成（あなたのスキーマに合わせて調整）
                    \DB::transaction(function () use ($itemId, $userId, $pi, $amount) {
                        $item = Item::lockForUpdate()->find($itemId);
                        if (!$item || (method_exists($item, 'isSold') && $item->isSold())) return;

                        Purchase::create([
                            'user_id' => $userId,
                            'item_id' => $itemId,
                            'amount'  => (int)$amount,
                            'stripe_payment_intent_id' => $pi->id,
                            'status'  => 'paid',
                        ]);

                        if ($item->fillable) {
                            $item->sold = true; // フラグを持っている場合
                            $item->save();
                        }
                    });
                }
                break;

            // 補助：Checkout完了イベント（コンビニは未払のまま完了することがある）
            case 'checkout.session.completed':
                // $session = $event->data->object; // $session->payment_status が 'paid' のときだけ確定してもOK
                break;
        }

        return response('ok', 200);
    }
}
