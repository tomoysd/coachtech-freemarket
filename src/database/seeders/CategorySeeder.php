<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            ['name' => 'ファッション',     'sort_order' => 1],
            ['name' => '家電',             'sort_order' => 2],
            ['name' => 'インテリア',       'sort_order' => 3],
            ['name' => 'レディース',       'sort_order' => 4],
            ['name' => 'メンズ',           'sort_order' => 5],
            ['name' => 'コスメ',           'sort_order' => 6],
            ['name' => '本',               'sort_order' => 7],
            ['name' => 'ゲーム',           'sort_order' => 8],
            ['name' => 'スポーツ',         'sort_order' => 9],
            ['name' => 'キッチン',         'sort_order' => 10],
            ['name' => 'ハンドメイド',     'sort_order' => 11],
            ['name' => 'アクセサリー',     'sort_order' => 12],
            ['name' => 'おもちゃ',         'sort_order' => 13],
            ['name' => 'ベビー・キッズ',   'sort_order' => 14],
        ];

        foreach ($rows as $row) {
            Category::updateOrCreate(
                ['name' => $row['name']],
                ['sort_order' => $row['sort_order']]
            );
        }
    }
}
