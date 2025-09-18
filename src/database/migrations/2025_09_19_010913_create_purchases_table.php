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
            $table->foreignId('item_id')->constrained();   // 1商品1件
            $table->foreignId('buyer_id')->constrained('users');
            $table->foreignId('seller_id')->constrained('users');
            $table->unsignedInteger('amount');
            $table->enum('payment_status', ['pending','paid','refunded'])->default('pending');
            $table->enum('shipping_status', ['pending','preparing','shipped','delivered','canceled'])->default('pending');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();

            $table->unique('item_id'); // 1商品1取引を担保
            $table->index(['buyer_id','created_at']);
            $table->index(['payment_status','shipping_status']);
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
