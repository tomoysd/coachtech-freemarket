@extends('layouts.auth')
@section('title','会員登録')

@section('content')
<div class="auth-card">
    <h1 class="auth-title">会員登録</h1>

    @if ($errors->any())
        <div class="auth-errors">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="post" class="auth-form">
        @csrf
        <div class="form-row">
            <label for="name">ユーザー名</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}">
        </div>

        <div class="form-row">
            <label for="email">メールアドレス</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}">
        </div>

        <div class="form-row">
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password">
        </div>

        <div class="form-row">
            <label for="password_confirmation">確認用パスワード</label>
            <input id="password_confirmation" name="password_confirmation" type="password">
        </div>

        <button class="auth-submit" type="submit">登録する</button>
    </form>

    <div class="auth-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </div>
</div>
@endsection
