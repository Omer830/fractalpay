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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->morphs('payable');
            $table->enum('type', \Modules\PaymentMethod\Enums\PaymentMethodTypes::toArray());
            $table->timestamps();
            $table->softDeletes();

            $table->index(['payable_type', 'payable_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
