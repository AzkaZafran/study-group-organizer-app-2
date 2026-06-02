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
        Schema::create('agenda', function (Blueprint $table) {
            $table->id('id_agenda');
            $table->string('nama_agenda', 50)->nullable(false);
            $table->foreignId('id_penyelenggara')->references('id')->on('users');
            $table->string('lokasi', 255)->nullable(false);
            $table->dateTime('waktu_mulai')->nullable(false);
            $table->dateTime('waktu_berakhir')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
