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
            'image'        => ['nullable', 'image', 'max:4096'], // 4MB
            // カテゴリは多対多なので配列で受ける（checkbox）
            'category_ids'     => ['required', 'array', 'min:1'],
            'category_ids.*'   => ['integer', 'exists:categories,id'],

            // condition は固定の選択肢のみ許可
            'condition'    => ['required', 'string', Rule::in(Item::CONDITIONS)],

            'title'        => ['required', 'string', 'max:100'],
            'brand'        => ['nullable', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
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
}
