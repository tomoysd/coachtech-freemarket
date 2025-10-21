<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'             => ['required', 'string', 'max:20'],
            'postal_code'      => ['nullable', 'string', 'max:8'],
            'address'          => ['nullable', 'string', 'max:255'],
            'building'         => ['nullable', 'string', 'max:255'],
            'avatar_path'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'ユーザー名',
            'postal_code'      => '郵便番号',
            'address'          => '住所',
            'building'         => '建物名',
            'avatar_path'      => 'プロフィール画像',
        ];
    }
}
