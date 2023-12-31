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
        Schema::create('real_states', function (Blueprint $table) {
            $table->id();     
            $table->unsignedBigInteger('address_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('description');
            $table->text('content');
            $table->float('price', 10, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('property_area');
            $table->integer('total_property_area');
            $table->string('slug');            
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_states');
    }
};
