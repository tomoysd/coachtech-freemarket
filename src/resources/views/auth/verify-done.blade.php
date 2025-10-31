@extends('layouts.auth')

@section('content')
<div class="verify-wrap">
    <h2 class="verify-done__title">メール認証が完了しました。</h2>
    <p class="verify-done__text">続いてプロフィール設定に進んでください。</p>

    <a href="{{ route('profile.edit') }}" class="verify-done__btn">プロフィール設定へ</a>
</div>
@endsection