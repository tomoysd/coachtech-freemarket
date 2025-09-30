@extends('layouts.auth')
@section('title','メール認証のお願い')

@section('content')
<div class="auth-card">
    <h1 class="auth-title">メール認証</h1>
    <p class="auth-text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <form method="post" action="{{ route('verification.send') }}" class="center">
        @csrf
        <button type="submit" class="auth-submit--ghost">認証メールを再送する</button>
    </form>

    <div class="auth-link">
        <a href="{{ route('login') }}">ログイン画面へ戻る</a>
    </div>
</div>
@endsection
