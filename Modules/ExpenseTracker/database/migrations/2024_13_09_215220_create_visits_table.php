<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\ExpenseTracker\Models\ExpenseUser;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visit_reason');
            $table->text('visit_details')->nullable();
            $table->foreignIdFor(ExpenseUser::class, 'user_id');
            $table->string('name')->nullable();
            $table->string('provider_number')->nullable();
            $table->string('organisation')->nullable();
            $table->string('visit_type')->default('default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
