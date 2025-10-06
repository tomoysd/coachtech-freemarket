<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // users(id)
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();   // items(id)
            $table->unsignedInteger('amount');                                // 仕様: unsigned integer
            $table->tinyInteger('status')->default(1);                         // 例: 1=購入済
            $table->timestamp('purchased_at');                                 // 購入日時
            $table->timestamps();                                              // created_at/updated_at
            $table->index(['item_id']);                                        // sold判定などの検索用
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
