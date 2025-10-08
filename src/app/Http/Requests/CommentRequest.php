<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // ログイン必須
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['body' => 'コメント'];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'コメントを入力してください',
            'body.max'      => 'コメントは255文字以内で入力してください',
        ];
    }
}
