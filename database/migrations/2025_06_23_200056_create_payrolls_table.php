<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->onDelete('set null')
                ->constrained('employees');
            $table->date('pay_date');
            $table->string('period');
            $table->decimal('gross_pay', 10, 2);
            $table->decimal('net_pay', 10, 2);
            $table->json('deductions')->nullable(); // e.g., taxes, insurance
            $table->json('allowances')->nullable(); // e.g., transport, housing
            $table->json('bonuses')->nullable(); // e.g., performance bonuses, holiday
            // $table->decimal('allowances', 10, 2)->default(0.00);
            // $table->decimal('bonuses', 10, 2)->default(0.00);
            // $table->text('deductions_details')->nullable();
            // $table->text('allowances_details')->nullable();
            // $table->text('bonuses_details')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // e.g., pending, completed
            $table->timestamps();

            $table->unique(['employee_id', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
