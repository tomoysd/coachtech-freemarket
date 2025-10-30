@extends('layouts.auth')

@section('content')
<div class="verify-wrap">
    <p class="verify-text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    @php
    $email = auth()->user()->email;
    $domain = \Illuminate\Support\Str::after($email, '@');
    $inbox = match($domain){
    'gmail.com' => 'https://mail.google.com',
    'yahoo.co.jp','yahoo.com' => 'https://mail.yahoo.co.jp',
    'outlook.com','hotmail.com','live.com' => 'https://outlook.live.com/mail/',
    default => '#',
    };
    @endphp

    <a href="{{ $inbox }}" target="_blank" rel="noopener" class="verify-btn">認証はこちらから</a>

    @if($inbox === '#')
    <p class="verify-note">
        ご利用中のメールサービスを開いて、認証メールをご確認ください。
    </p>
    @endif

    {{-- 再送リンク --}}
    <form method="POST" action="{{ route('verification.send') }}" class="verify-resend">
        @csrf
        <button type="submit" class="verify-resend__link">認証メールを再送する</button>
    </form>

    @if (session('status') === 'verification-link-sent')
    <p class="verify-status">認証メールを再送しました。受信ボックスをご確認ください。</p>
    @endif
</div>
@endsection