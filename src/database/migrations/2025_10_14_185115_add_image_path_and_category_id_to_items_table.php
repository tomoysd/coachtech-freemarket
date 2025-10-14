<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagePathAndCategoryIdToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // 既存カラム: id, user_id, title, description, brand, price, condition_id, timestamps を想定

            // 画像パス（UIで必須にするが、既存データ考慮でnullableに）
            $table->string('image_path')->nullable()->after('price');

            // カテゴリ（UIで必須にするが、既存データ考慮でnullableに）
            $table->unsignedTinyInteger('category_id')->nullable()->after('image_path');

            // 外部キー
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->restrictOnDelete()->cascadeOnUpdate();

            // もし condition_id のFKがまだなら有効化（あるならここは無視される）
            if (!collect($table->getColumns())->contains('condition_id')) {
                // 既にある想定なので何もしない
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'image_path']);
        });
    }
}
