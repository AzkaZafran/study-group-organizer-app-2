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
        Schema::create('catatan_terbaca', function (Blueprint $table) {
            $table->id('id_catatan_terbaca');
            $table->foreignId('id_user')->references('id')->on('users');
            $table->foreignId('id_catatan')->references('id_catatan')->on('catatan');
            $table->string('status', 20)->default('belum dibaca')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_terbacas');
    }
};
