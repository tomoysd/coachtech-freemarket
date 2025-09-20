@extends('layouts.app')

@section('title', 'ログイン')

@section('content')
<div class="auth-container">
    <h1>ログイン</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required>
        </div>

        <button type="submit">ログイン</button>
    </form>
</div>
@endsection