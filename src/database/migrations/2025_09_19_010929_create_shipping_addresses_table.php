<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name', 50);
            $table->char('postal_code', 8);
            $table->string('prefecture', 20);
            $table->string('city', 50);
            $table->string('address_line1', 100);
            $table->string('address_line2', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();

            $table->unique('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_addresses');
    }
}
