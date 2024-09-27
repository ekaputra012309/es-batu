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
        // Create the header table
        Schema::create('table_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_inv')->unique(); // Invoice number
            $table->decimal('total', 10, 2); // Total amount
            $table->unsignedBigInteger('user_id'); // User ID
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create the detail table
        Schema::create('table_transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_transaksi_id'); // Foreign key referencing the header table
            $table->string('no_inv'); // Invoice number
            $table->integer('qty'); // Quantity
            $table->decimal('harga', 10, 2); // Price
            $table->string('satuan'); // Satuan
            $table->unsignedBigInteger('user_id'); // User ID
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('table_transaksi_id')->references('id')->on('table_transaksis')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_transaksi_details');
        Schema::dropIfExists('table_transaksis');
    }
};
