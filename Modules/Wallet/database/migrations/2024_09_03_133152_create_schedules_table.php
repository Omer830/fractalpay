<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Wallet\Enums\TransactionStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();
            $table->foreignIdFor(\Modules\Wallet\Models\WalletUser::class, 'user_id')->constrained();
            $table->enum('type', \Modules\Wallet\Enums\TransactionType::toArray());


            $table->string('currency')->default(\Modules\Wallet\Enums\Currency::AUD->value);
            $table->decimal('amount', 15, 2)->default(0);

            $table->string('base_currency')->default(\Modules\Wallet\Enums\Currency::EUR->value);
            $table->decimal('exchange_rate', 10, 6)->default(0.00);


            $table->enum('frequency', \Modules\Wallet\Enums\TransactionFrequency::toArray());

            $table->foreignIdFor(\Modules\Wallet\Models\Wallet::class)
                ->constrained();

            $table->timestamp('start_date')->nullable();
            $table->timestamp('next_run_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->string('receivable_type')->nullable();
            $table->string('receivable_id')->nullable();

            $table->foreignIdFor(\Modules\PaymentMethod\Models\PaymentMethod::class);

            $table->string('description')->nullable();

            $table->enum('schedule_status', TransactionStatus::toArray())
                ->default(TransactionStatus::ACTIVE->value);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'next_run_date']);
            $table->index(['type', 'user_id', 'frequency']);
            $table->index(['receivable_type', 'receivable_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
