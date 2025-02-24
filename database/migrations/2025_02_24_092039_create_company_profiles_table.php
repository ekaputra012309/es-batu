<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Company name
            $table->string('address')->nullable(); // Company address
            $table->string('phone')->nullable(); // Contact number
            $table->string('email')->nullable(); // Website URL
            $table->string('website')->nullable();
            $table->integer('logo')->default(1);
            $table->text('description')->nullable(); // Description of the company
            $table->string('image')->nullable(); // Path to image file
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }
}