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
        Schema::create('tool_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('day')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->foreignId('address_id')->constrained('addresses')->cascadeOnDelete();
            $table->float('rent_fee')->nullable();
            $table->float('deposit')->nullable();
            $table->float('platform_fee')->nullable();
            $table->float('total_amount')->nullable();
            $table->enum('status', ['pending','confirmed','in_progress','completed','cancelled'])->default('pending');
            $table->boolean('agree_to_terms')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_bookings');
    }
};
