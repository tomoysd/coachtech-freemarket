@extends('layouts.app')

@section('content')
<div class="container" style="max-width:720px;margin:40px auto;">
    <h2>配送先の変更</h2>

    <form method="POST" action="{{ route('shipping.update', ['item_id' => $item->id]) }}">
        @method('PATCH')
        @csrf

        <div class="form-group">
            <label>受取人氏名</label>
            <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}">
            @error('recipient_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}">
            @error('postal_code')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>都道府県</label>
            <input type="text" name="prefecture" value="{{ old('prefecture', $address->prefecture) }}">
            @error('prefecture')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>住所1</label>
            <input type="text" name="address1" value="{{ old('address1', $address->address1) }}">
            @error('address1')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>住所2</label>
            <input type="text" name="address2" value="{{ old('address2', $address->address2) }}">
            @error('address2')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>電話番号</label>
            <input type="text" name="phone" value="{{ old('phone', $address->phone) }}">
            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit">保存して戻る</button>
    </form>
</div>
@endsection