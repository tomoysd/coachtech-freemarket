<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;
use Illuminate\Validation\Rule;

class ExhibitionRequest extends FormRequest
{

    protected $errorBag = 'exhibition';
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
            'image'        => ['required', 'nullable', 'image', 'max:4096'], // 4MB
            // カテゴリは多対多なので配列で受ける（checkbox）
            'category_ids'     => ['required', 'array', 'min:1'],
            'category_ids.*'   => ['integer', 'exists:categories,id'],

            // condition は固定の選択肢のみ許可
            'condition'    => ['required', 'integer', Rule::in(array_keys(Item::CONDITIONS))],

            'title'        => ['required', 'string', 'max:100'],
            'brand'        => ['nullable', 'string', 'max:100'],
            'description'  => ['required', 'nullable', 'string', 'max:255'],
            'price'        => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes(): array
    {
        return [
            'image'         => '商品画像',
            'category_ids'  => 'カテゴリー',
            'condition'     => '商品の状態',
            'title'         => '商品名',
            'brand'         => 'ブランド',
            'description'   => '商品説明',
            'price'         => '商品価格',
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => '商品画像は必須項目です。',
            'image.image'    => 'アップロードできるのは画像ファイルのみです。',
            'image.mimes'    => ' jpeg / png  のいずれかでアップロードしてください。',
            'condition.required' => '商品の状態は必須項目です。',
            'condition.in'       => '選択した 商品の状態 が不正です。',
        ];
    }
}
