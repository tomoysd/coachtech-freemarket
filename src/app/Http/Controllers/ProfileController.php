<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * プロフィール更新処理
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // バリデーション結果を取得
        $data = $request->validated();

        DB::transaction(function () use ($user, $data, $request) {

            // --- ① ユーザー名の更新 ---
            $user->name = $data['name'];
            $user->save();

            // --- ② プロフィール情報の作成または更新 ---
            $profile = Profile::firstOrCreate(
                ['user_id' => $user->id],
                ['postal_code' => '', 'address' => '', 'building' => '', 'avatar_path' => '']
            );

            // 住所系の更新
            $profile->postal_code = $data['postal_code'] ?? '';
            $profile->address = $data['address'] ?? '';
            $profile->building = $data['building'] ?? '';

            // --- ③ 画像アップロード処理 ---
            if ($request->hasFile('avatar')) {

                // 古い画像があれば削除
                if (!empty($profile->avatar_path) && Storage::disk('public')->exists($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                }

                // 新しい画像を保存
                $path = $request->file('avatar')->store('avatars', 'public');
                $profile->avatar_path = $path;
            }

            // 保存
            $profile->save();
        });

        // 更新完了後、マイページまたは一覧にリダイレクト
        return redirect('/')
            ->with('message', 'プロフィールを更新しました。');
    }
}
