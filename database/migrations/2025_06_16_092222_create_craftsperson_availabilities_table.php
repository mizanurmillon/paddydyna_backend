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
        Schema::create('craftsperson_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('craftspeople_id');
            $table->foreign('craftspeople_id')->references('id')->on('craftspeople')->onDelete('cascade');
            $table->string('day')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('craftsperson_availabilities');
    }
};
