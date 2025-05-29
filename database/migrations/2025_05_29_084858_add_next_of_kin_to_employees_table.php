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
        Schema::table('employees', function (Blueprint $table) {
            //
            $table->string('next_of_kin_name')->nullable()->after('phone');
            $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_name');
            $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_relationship');
            $table->string('next_of_kin_email')->nullable()->after('next_of_kin_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
            $table->dropColumn('next_of_kin_name');
            $table->dropColumn('next_of_kin_relationship');
            $table->dropColumn('next_of_kin_phone');
            $table->dropColumn('next_of_kin_email');
        });
    }
};
