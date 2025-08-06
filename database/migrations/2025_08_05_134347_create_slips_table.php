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
        Schema::create('slips', function (Blueprint $table) {
            $table->id();
            $table->datetime('tanggal');
            $table->integer('hari_kerja');
            $table->string('nama');
            $table->integer('gp')->default(0);
            $table->integer('inssentif')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('uang_makan')->default(0);
            $table->integer('pot_uang_makan')->default(0);
            $table->integer('pot_kasbon')->default(0);
            $table->integer('hutang')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slips');
    }
};
