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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the foreign key constraint on user_id
            $table->dropForeign(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Re-add the foreign key if needed in rollback
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
