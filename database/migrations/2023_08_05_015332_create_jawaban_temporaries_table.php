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
        Schema::create('jawaban_temporary', function (Blueprint $table) {
            $table->id();
            $table->string('id_murid');
            $table->string('id_ujian');
            $table->text('yang_udah_dikerjain')->nullable();
            $table->boolean('is_finish')->default(false);
            $table->boolean('is_nyontek')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_temporary');
    }
};
