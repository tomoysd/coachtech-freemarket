@extends('layouts.app')

@section('title', '新規登録')

@section('content')
<div class="auth-container">
    <h1>新規登録</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input id="name" type="text" name="name" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">パスワード確認</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button type="submit">登録する</button>
    </form>
</div>
@endsection