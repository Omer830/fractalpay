<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Enums\TransactionStatus;
use Modules\Wallet\Enums\TransactionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            $table->string('transaction_id')->index();

            $table->foreignIdFor(\Modules\Wallet\Models\WalletUser::class, 'user_id')
                ->constrained();

            $table->string('receivable_type')->nullable();
            $table->string('receivable_id')->nullable();

            $table->enum('type', TransactionType::toArray());

            $table->string('base_currency')->default(Currency::EUR->value);

            $table->string('currency');
            $table->decimal('amount', 16,2);


            $table->string('sender_currency')->default(Currency::AUD->value);
            $table->decimal('sender_amount', 10, 2)->default(0);

            $table->string('receiver_currency')->default(Currency::AUD->value);
            $table->decimal('receiver_amount', 10, 2)->default(0);

            $table->decimal('conversion_rate', 10, 2)->default(0);

            $table->enum('status', TransactionStatus::toArray())->default(TransactionStatus::PENDING->value);


            $table->index(['receivable_type', 'receivable_id']);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
