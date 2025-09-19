<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->profile; // 1:1
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'display_name' => ['nullable','string','max:50'],
            'bio'          => ['nullable','string','max:160'],
            'avatar'       => ['nullable','image','max:4096'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($validated['avatar']);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->route('mypage')->with('message','プロフィールを更新しました');
    }
}
