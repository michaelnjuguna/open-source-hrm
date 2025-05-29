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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            // $table->decimal('hours_worked', 5, 2)->nullable();
            $table->foreignId('shift_id')->nullable()->constrained()->onDelete('set null');
            $table->text('remarks')->nullable();
            $table->timestamps();

            // $table->unique(['employee_id', 'date'], 'unique_employee_date');
            $table->unique(['employee_id', 'date', 'shift_id'], 'unique_employee_date_shift');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');

    }
};
