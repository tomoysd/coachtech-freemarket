<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // 未認証なら：メール再送して誘導画面へ
        if (!$user->hasVerifiedEmail()) {
            // 任意：最新の検証メールを再送
            $user->sendEmailVerificationNotification();

            // ログイン状態は維持 or 直ちにログアウト、どちらでもOK
            // 未認証でアプリを触らせたくないならログアウト推奨：
            Auth::logout();

            return redirect()
                ->route('verification.notice')
                ->with('status', 'verification-link-sent');
        }
        // intended があればそこへ。なければ商品一覧へ。
        return redirect()->intended(route('items.index'));
    }
}
