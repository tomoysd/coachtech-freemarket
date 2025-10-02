<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // 登録画面表示
    public function create()
    {
        return view('auth.register');
    }

    // 登録処理
    public function store(RegisterRequest $request)
    {
        // バリデーションは RegisterRequest が実施
        $user = User::create([
            'name'     => $request->input('name'),     // ユーザー名
            'email'    => $request->input('email'),    // メール
            'password' => Hash::make($request->input('password')),
        ]);

        event(new Registered($user)); // メール認証ON時はここで通知が飛ぶ

        Auth::login($user); // 登録直後にログイン状態にする

        // === 遷移先の制御 ===
        // 1) メール認証機能を使う場合：認証案内画面へ
        // 2) 使わない場合：プロフィール設定画面へ
        // if (method_exists($user, 'hasVerifiedEmail')) {
        //     // User に MustVerifyEmail を付けていれば true
        //     if (! $user->hasVerifiedEmail()) {
        //         return redirect()->route('verification.notice'); // /email/verify
        //     }
        // }

        return redirect()->route('profile.edit');
    }
}
