<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->unique();
            $table->string('recipient_name', 255); // users.nameをコピー（編集不可）
            $table->string('postal_code', 10);
            $table->string('prefecture', 50);
            $table->string('address1', 255);
            $table->string('address2', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();

            // 外部キー制約
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
