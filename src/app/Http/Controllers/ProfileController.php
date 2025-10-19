<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // 必要になったら FormRequest 化してください（今回の要件は画面遷移のみでOK）
        $data = $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $data, $request) {
            // usersテーブル側
            $user->name = $data['name'];
            $user->save();

            // profiles を確実に用意
            $profile = $user->profile()->firstOrCreate([], [
                'postal_code' => $data['postal_code'] ?? '',
                'address'     => $data['address'] ?? '',
                'building'    => $data['building'] ?? '',
            ]);

            // 画像が送られていれば保存（storage/app/public/avatars/..）
            if ($request->hasFile('avatar')) {
                // 旧画像があれば削除
                if (!empty($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $profile->avatar_path = $path;
            }

            // 住所系
            $profile->postal_code = $data['postal_code'] ?? '';
            $profile->address     = $data['address'] ?? '';
            $profile->building    = $data['building'] ?? '';
            $profile->save();
        });

        return back()->with('message', '更新しました');
    }
}
