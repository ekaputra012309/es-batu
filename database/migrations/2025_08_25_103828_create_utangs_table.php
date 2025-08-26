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
        Schema::create('utang_besars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_transaksi_id');
            $table->datetime('tanggal')->nullable();
            $table->decimal('nominal', 10, 0)->nullable();
            $table->unsignedBigInteger('user_id'); // User ID
            $table->enum('status', [0, 1])->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('table_transaksi_id')->references('id')->on('table_transaksis')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('cicil_utangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_utang_id');
            $table->datetime('tanggal')->nullable();
            $table->decimal('nominal', 10, 0)->nullable();
            $table->unsignedBigInteger('user_id'); // User ID
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('table_utang_id')->references('id')->on('utang_besars')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utang_besars');
        Schema::dropIfExists('cicil_utangs');
    }
};
