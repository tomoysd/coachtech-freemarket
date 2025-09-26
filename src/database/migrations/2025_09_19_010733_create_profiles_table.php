<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id(); // unsigned bigint
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // users(id)
            $table->string('avatar_path', 255)->nullable();
            $table->string('postal_code', 10);
            $table->string('prefecture', 50);
            $table->string('address1', 255);
            $table->string('address2', 255)->nullable();
            $table->string('phone', 20);

            $table->timestamps(); // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
