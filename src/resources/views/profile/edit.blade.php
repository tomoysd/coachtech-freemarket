@extends('layouts.auth')
@section('title','プロフィール設定')

@section('content')
<div class="auth-card">
    <h1 class="auth-title">プロフィール設定</h1>

    <div class="mp-avatar">
        @php $avatar = optional($user->profile)->avatar_path; @endphp
        @if($avatar)
        <img src="{{ asset($avatar) }}" alt="プロフィール画像">
        @else
        <div class="avatar-ph"></div>
        @endif
    </div>

    @if(session('message'))
    <div class="auth-flash">{{ session('message') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="post" class="auth-form">
        @csrf
        @method('PATCH')
        <div class="form-row">
            <label for="name">ユーザー名</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-row">
            <label for="postal_code">郵便番号</label>
            <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}">
        </div>

        <div class="form-row">
            <label for="address">住所</label>
            <input id="address" name="address" type="text" value="{{ old('address') }}">
        </div>

        <div class="form-row">
            <label for="building">建物名</label>
            <input id="building" name="building" type="text" value="{{ old('building') }}">
        </div>

        <button class="auth-submit" type="submit">更新する</button>
    </form>
</div>
@endsection