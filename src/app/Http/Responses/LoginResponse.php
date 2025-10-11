<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // intended があればそこへ。なければ商品一覧へ。
        return redirect()->intended(route('items.index'));
    }
}
