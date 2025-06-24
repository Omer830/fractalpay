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
        Schema::table('users', function (Blueprint $table) {
            $table->string('secondary_email_otp')->nullable();
            $table->string('alternative_email_otp')->nullable();
            $table->timestamp('secondary_email_verified_at')->nullable();
            $table->timestamp('alternative_email_verified_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['secondary_email']);
            $table->dropUnique(['alternative_email']);
            $table->dropColumn(['secondary_email', 'alternative_email']);
        });
    }
};
