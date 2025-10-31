@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
<div class="purchase-container">
    {{-- 左カラム --}}
    <div class="purchase-left">
        <div class="purchase-item">
            <div class="item-image">
                <img src="{{ $item->image_url }}" alt="商品画像">
            </div>
            <div class="item-info">
                <p class="item-name">{{ $item->title ?? $item->name }}</p>
                <p class="item-price">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>

        {{-- 支払い方法（GETで反映） --}}
        <div class="payment-section">
            <label for="payment_method">支払い方法</label>

            <form method="GET" action="{{ route('purchase.create', ['item_id' => $item->id]) }}" class="method-form">
                <select name="payment_method" id="payment_method">
                    <option value="">選択してください</option>
                    <option value="1"
                        @if($paymentMethod==='1' || $paymentMethod===1 ) selected @endif>コンビニ支払い</option>
                    <option value="2"
                        @if($paymentMethod==='2' || $paymentMethod===2 ) selected @endif>カード支払い</option>
                </select>
                <button type="submit" class="reflect-btn">反映する</button>
            </form>
            {{-- バリデーションエラー表示（POSTのとき） --}}
            @error('payment_method')
            <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- 配送先 --}}
        <div class="shipping-section">
            <div class="shipping-header">
                <span>配送先</span>
                <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="change-link">変更する</a>
            </div>
            <div class="shipping-body">
                〒 {{ $shipping->postal_code }}<br>
                {{ $shipping->address }}{{ $shipping->building }}
            </div>
        </div>

        {{-- 購入確定（POST） --}}
        <form method="POST" action="{{ route('purchase.checkout', ['item' => $item->id]) }}" class="buy-form">
            @csrf
            <input type="hidden" name="payment_method"
                value="{{ $paymentMethod == 1 ? 'konbini' : ($paymentMethod == 2 ? 'card' : '') }}">
            <button type="submit" class="purchase-btn">購入する</button>
        </form>
    </div>

    {{-- 右カラム：サマリー --}}
    <div class="purchase-summary">
        <div class="summary-box">
            <div class="row">
                <span>商品代金</span>
                <span>¥ {{ number_format($item->price) }}</span>
            </div>
            <div class="row">
                <span>支払い方法</span>
                <span class="method">
                    @if($paymentMethod == 1)
                    コンビニ支払い
                    @elseif($paymentMethod == 2)
                    カード支払い
                    @else
                    未選択
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
@endsection