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
        Schema::create('leave', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('actioned_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');// pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave');
    }
};
