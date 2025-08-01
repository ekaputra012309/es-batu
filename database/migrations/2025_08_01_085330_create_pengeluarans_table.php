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
        Schema::create('pengeluaran_headers', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('pengeluaran_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_header_id')->constrained('pengeluaran_headers')->onDelete('cascade');
            $table->decimal('nominal', 10, 0)->nullable();
            $table->string('keterangan');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_headers');
        Schema::dropIfExists('pengeluaran_details');
    }
};
