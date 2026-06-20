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
        Schema::create('partisipan', function (Blueprint $table) {
            $table->id('id_partisipan');
            $table->foreignId('id_agenda')->references('id_agenda')->on('agenda');
            $table->foreignId('id_user')->references('id')->on('users');
            $table->string('status', 20)->default('pending')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partisipan');
    }
};
