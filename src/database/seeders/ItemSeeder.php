<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\User;



class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 出品者ID（上の UserSeeder で作ったユーザーを使う）
        $sellerId = User::where('email', 'seller@example.com')->value('id');

        // まず既存の関連を消す（順序注意：pivot→親）
        DB::table('item_categories')->delete();
        DB::table('favorites')->delete();
        DB::table('purchases')->delete();
        DB::table('shipping_addresses')->delete();
        DB::table('items')->delete();

        // === ここにシートの行を“そのまま”並べるだけ ===
        // 価格はカンマ付きでもOK（下で数値化します）
        $rows = [
            [
                'title' => '腕時計',
                'price' => '15,000',
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'categories'  => ['メンズ', 'ファッション'],
            ],
            [
                'title' => 'HDD',
                'price' => '5,000',
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'categories'  => ['家電'],
            ],
            [
                'title' => '玉ねぎ3束',
                'price' => '600',
                'brand' => '新森本',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'categories'  => ['キッチン'],
            ],
            [
                'title' => '革靴',
                'price' => '4,000',
                'brand' => null, // シートが「なし」の場合は null にするのがおすすめ
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'categories'  => ['メンズ', 'ファッション'],
            ],
            [
                'title' => 'ノートPC',
                'price' => '45,000',
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'categories'  => ['家電'],
            ],
            [
                'title' => 'マイク',
                'price' => '8,000',
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'categories'  => ['家電', 'おもちゃ'],
            ],
            [
                'title' => 'ショルダーバッグ',
                'price' => '9,000',
                'brand' => null,
                'description' => '使いやすいショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'categories'  => ['レディース', 'ファッション'],
            ],
            [
                'title' => 'タンブラー',
                'price' => '500',
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'categories'  => ['キッチン'],
            ],
            [
                'title' => 'コーヒーミル',
                'price' => '4,000',
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'categories'  => ['キッチン', 'ハンドメイド'],
            ],
            [
                'title' => 'メイクセット',
                'price' => '2,500',
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => '目立った傷や汚れなし',
                'categories'  => ['レディース', 'コスメ'],
            ],
        ];


        foreach ($rows as $r) {
            $item = Item::create([
                'user_id'     => $sellerId,
                'title'       => $r['title'],
                'brand'       => $r['brand'],
                'description' => $r['description'],
                'image_url'   => $r['image_url'],
                'price'       => (int)str_replace([',', '円'], '', (string)$r['price']),
                'condition'   => array_search($r['condition'], Item::CONDITIONS) ?: 1, // ← 文字を数値に変換
            ]);
            // カテゴリ名 → id へ変換して pivot を attach
            if (!empty($r['categories'])) {
                $ids = collect($r['categories'])
                    ->map(function ($name) use($item) {
                        // 余計な空白や全角を正規化
                        $normalized = trim(mb_convert_kana($name, 's')); // 全角スペースを半角に
                        return ["item_id" => $item->id, "category_id" => Category::where(['name' => $normalized])->first()->id];
                    })
                    ->all();
                $item->categories()->attach($ids);
            }
        }
    }
}
