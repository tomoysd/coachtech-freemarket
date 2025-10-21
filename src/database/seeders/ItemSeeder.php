<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class ItemSeeder extends Seeder
{
    /** コンディション名 → ID のマップ */
    private const COND = [
        '良好' => 1,
        '目立った傷や汚れなし' => 2,
        'やや傷や汚れあり' => 3,
        '状態が悪い' => 4,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 出品者ID（上の UserSeeder で作ったユーザーを使う）
        $sellerId = DB::table('users')->where('email', 'seller@example.com')->value('id') ?? 1;

        // === ここにシートの行を“そのまま”並べるだけ ===
        // 価格はカンマ付きでもOK（下で数値化します）
        $rows = [
            [
                'title' => '腕時計',
                'price' => '15,000',
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_name' => '良好',
            ],
            [
                'title' => 'HDD',
                'price' => '5,000',
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_name' => '目立った傷や汚れなし',
            ],
            [
                'title' => '玉ねぎ3束',
                'price' => '600',
                'brand' => '新森本',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_name' => 'やや傷や汚れあり',
            ],
            [
                'title' => '革靴',
                'price' => '4,000',
                'brand' => null, // シートが「なし」の場合は null にするのがおすすめ
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_name' => '状態が悪い',
            ],
            [
                'title' => 'ノートPC',
                'price' => '45,000',
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_name' => '良好',
            ],
            [
                'title' => 'マイク',
                'price' => '8,000',
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_name' => '目立った傷や汚れなし',
            ],
            [
                'title' => 'ショルダーバッグ',
                'price' => '9,000',
                'brand' => null,
                'description' => '使いやすいショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_name' => 'やや傷や汚れあり',
            ],
            [
                'title' => 'タンブラー',
                'price' => '500',
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_name' => '状態が悪い',
            ],
            [
                'title' => 'コーヒーミル',
                'price' => '4,000',
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_name' => '良好',
            ],
            [
                'title' => 'メイクセット',
                'price' => '2,500',
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_name' => '目立った傷や汚れなし',
            ],
        ];


        DB::table('items')->delete();
        // 参照する子テーブルもデータを消したいなら先に delete
        DB::table('favorites')->delete();
        DB::table('purchases')->delete();
        DB::table('shipping_addresses')->delete();


        foreach ($rows as $r) {
            DB::table('items')->insert([
                'user_id'       => $sellerId,
                'title'         => $r['title'],
                'brand'         => $this->normalizeBrand($r['brand']),
                'description'   => $r['description'],
                'image_url'     => $r['image_url'],
                'price'         => $this->toInt($r['price']),
                'condition'  => self::COND[$r['condition_name']] ?? 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    private function toInt(string $price): int
    {
        // 「15,000」→ 15000 、「¥」や空白があっても除去
        return (int)preg_replace('/[^\d]/', '', $price);
    }

    private function normalizeBrand($brand)
    {
        if (is_null($brand)) return null;
        $b = trim($brand);
        if ($b === '' || $b === 'なし') return null;
        return $b;
    }
}
