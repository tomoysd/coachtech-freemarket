@extends('layouts.auth')
@section('title','プロフィール設定')

@section('content')
<div class="auth-card">
    <h1 class="auth-title">プロフィール設定</h1>

    <div class="mp-avatar-row">
        {{-- 左：現在のアイコン（またはプレースホルダー） --}}
        <div class="mp-avatar">
            @php $avatar = optional($user->profile)->avatar_url; @endphp
            @if($avatar)
            <img src="{{ $avatar }}" alt="プロフィール画像">
            @else
            <span aria-hidden="true"></span> {{-- 中身はCSSで丸い灰色にする --}}
            @endif
        </div>

        {{-- 右：赤い擬似ボタン（実体は label） --}}
        <div class="mp-avatar-actions">
            <input id="avatar" name="avatar" type="file" accept="image/*" class="file-input">
            <label for="avatar" class="btn-image">画像を選択する</label>
            @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
        </div>
    </div>


    <form action="{{ route('profile.update') }}" method="post" class="auth-form">
        @csrf
        @method('PATCH')

        <div class="form-row">
            <label for="name">ユーザー名</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>


    <div class="form-row">
        <label for="postal_code">郵便番号</label>
        <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', optional($user->profile)->postal_code) }}">
        @error('postal_code') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-row">
        <label for="address">住所</label>
        <input id="address" name="address" type="text" value="{{ old('address', optional($user->profile)->address) }}">
        @error('address') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-row">
        <label for="building">建物名</label>
        <input id="building" name="building" type="text" value="{{ old('building', optional($user->profile)->building) }}">
        @error('building') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <button class="auth-submit" type="submit">更新する</button>
    </form>
</div>
@endsection