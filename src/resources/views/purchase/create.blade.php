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
                <img src="{{ $item->image }}" alt="商品画像">
            </div>
            <div class="item-info">
                <p class="item-name">{{ $item->title }}</p>
                <p class="item-price">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>

        {{-- 支払い方法（GETで反映） --}}
        <div class="payment-section">
            <label for="payment_method">支払い方法</label>

            <form method="GET" action="{{ route('purchase.create', ['item_id' => $item->id]) }}" class="method-form">
                <select name="payment_method" id="payment_method">
                    <option value="">選択してください</option>
                    <option value="convenience_store"
                        @selected(request('payment_method')==='convenience_store')>コンビニ支払い</option>
                    <option value="credit_card"
                        @selected(request('payment_method')==='credit_card')>カード支払い</option>
                </select>
                <button type="submit" class="reflect-btn">反映する</button>
            </form>
        </div>

        {{-- 配送先 --}}
        <div class="shipping-section">
            <div class="shipping-header">
                <span>配送先</span>
                <a href="{{ route('shipping.edit', ['item_id' => $item->id]) }}" class="change-link">変更する</a>
            </div>
            <div class="shipping-body">
                <p>〒 {{ $shipping->postal_code }}</p>
                <p>{{ $shipping->prefecture }}{{ $shipping->address1 }}{{ $shipping->address2 }}</p>
                @if($shipping->phone)
                    <p>{{ $shipping->phone }}</p>
                @endif
            </div>
        </div>

        {{-- 購入確定（POST） --}}
        <form method="POST" action="{{ route('purchase.store', ['item_id' => $item->id]) }}">
            @csrf
            <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
            @error('payment_method')
                <p class="form-error">{{ $message }}</p>
            @enderror

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
                    @php
                        $map = ['convenience_store'=>'コンビニ支払い','credit_card'=>'カード支払い'];
                        $label = $map[request('payment_method')] ?? '未選択';
                    @endphp
                    {{ $label }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
