@extends('layouts.auth')

@section('title', 'ログイン')

@section('content')
<div class="auth-card">
    <h1 class="auth-title">ログイン</h1>

    {{-- バリデーションエラー表示（任意） --}}
    @if ($errors->any())
    <div class="auth-errors">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="auth-form">
        @csrf

        <div class="auth-form_row">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" required autofocus>
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-form_row">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required>
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-submit">ログインする</button>

        @if(session('message'))
        <p class="success">{{ session('message') }}</p>
        @endif
    </form>

    <div class="auth-link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection