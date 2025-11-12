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
        Schema::table('user_updates', function (Blueprint $table) {
            $table->string('garda_vetting_certificate')->nullable()->after('driving_license_or_passport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_updates', function (Blueprint $table) {
            //
        });
    }
};
