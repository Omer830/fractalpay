<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\UserKyc\Enums\Gender;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_name')->after('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('state')->after('address')->nullable();
            $table->string('city')->after('state')->nullable();
            $table->date('date_of_birth')->after('email')->nullable();
            $table->string('gender')->after('date_of_birth')->nullable();
//            $table->foreignIdFor(\Modules\Options\Models\Options::class, 'country_id')->nullable();
            $table->string('postal_code')->nullable();
        });

        if(Schema::hasColumn('users', 'country')) {
            Schema::dropColumns('users', 'country');
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn(['user_name', 'address', 'state', 'city', 'date_of_birth', 'gender', 'postal_code']);
        });
    }
};
