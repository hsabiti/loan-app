<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_events', function (Blueprint $table) {
            $table->id();

            // Foreign key to loans table
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');

            // Type of event: 'rate_change', 'part_payment', 'early_payoff'
            $table->enum('type', ['rate_change', 'part_payment', 'early_payoff']);

            // The date of the event
            $table->date('event_date');

            // Optional amount (for part_payment or payoff)
            $table->decimal('amount', 15, 2)->nullable();

            // Optional new rate (for rate_change)
            $table->decimal('rate', 5, 4)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_events');
    }
};
