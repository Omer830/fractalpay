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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base')->default(\Modules\Wallet\Enums\Currency::EUR->value);
            $table->string('currency');
            $table->decimal('rate', 16, 6)->default(1); // Precision of 16, scale of 6
            $table->bigInteger('timestamp')->default(time());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
