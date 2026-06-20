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
        Schema::create('undangan_agenda', function (Blueprint $table) {
            $table->id('id_invite');
            $table->foreignId('id_agenda')->references('id_agenda')->on('agenda');
            $table->string('invite_code', 15)->nullable(false);
            $table->dateTime('expired_at')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('undangan_agenda');
    }
};
