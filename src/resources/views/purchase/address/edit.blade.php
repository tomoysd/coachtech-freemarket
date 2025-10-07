@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('title', '送付先住所変更画面')

@section('content')
<div class="address-edit">
    <h1 class="address-edit__title">住所の変更</h1>

    @if ($errors->any())
    <div class="form-alert form-alert--danger">
        <ul>
            @foreach ($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="address-edit__form" method="POST" action="{{ route('purchase.address.update', $item) }}">
        @csrf
        @method('PATCH')

        <div class="form-row">
            <label class="form-label" for="postal_code">郵便番号</label>
            <input id="postal_code" name="postal_code" type="text" class="form-input"
                value="{{ old('postal_code', $draft->postal_code ?? '') }}" placeholder="">
        </div>

        <div class="form-row">
            <label class="form-label" for="address">住所</label>
            <input id="address" name="address" type="text" class="form-input"
                value="{{ old('address', $draft->address ?? '') }}" placeholder="">
        </div>

        <div class="form-row">
            <label class="form-label" for="building">建物名</label>
            <input id="building" name="building" type="text" class="form-input"
                value="{{ old('building', $draft->building ?? '') }}" placeholder="">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary">更新する</button>
        </div>
    </form>
</div>
@endsection

