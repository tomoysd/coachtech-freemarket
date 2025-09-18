<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 出品者
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->unsignedInteger('price'); // 円
            $table->unsignedTinyInteger('condition_id'); // conditions.id
            $table->enum('status', ['listed','sold','hidden'])->default('listed');
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();

            $table->foreign('condition_id')->references('id')->on('conditions');
            $table->index('user_id');
            $table->index(['status','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
