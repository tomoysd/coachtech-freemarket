<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * ログインフォーム表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(LoginRequest $request)
    {
        // フォームリクエストでバリデーション済みのデータを取得
        $credentials = $request->only('email', 'password');

        // ログイン試行
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // セッション固定攻撃対策
            return redirect()->intended('/'); // ログイン成功 → トップページ
        }

        // 認証失敗時
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが間違っています。',
        ])->onlyInput('email');
    }

    /**
     * ログアウト処理
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login')->with('message', 'ログアウトしました。');
    }
}
