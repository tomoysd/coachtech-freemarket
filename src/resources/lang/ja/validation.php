<?php

return [
    'required' => ':attributeは必須項目です。',
    'email' => ':attributeには有効なメールアドレスを指定してください。',
    'unique' => ':attributeは既に使用されています。',

    'in'       => '選択した :attribute が不正です。',
    'integer'  => ':attribute は整数で入力してください。',
    'min' => [
        'numeric' => ':attribute は :min 以上で入力してください。',
    ],
    'max' => [
        'string'  => ':attribute は :max 文字以内で入力してください。',
    ],
    // 画面に出すラベル名
    'attributes' => [
        'condition'   => '商品の状態',
        'name'        => '商品名',
        'brand'       => 'ブランド名',
        'description' => '商品の説明',
        'price'       => '商品価格',
        'image'       => '商品画像',
        'categories'  => 'カテゴリー',
    ],
];
