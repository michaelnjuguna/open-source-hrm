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
        Schema::create('topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('subject');
            $table->foreignUuid('creator_id');
            $table->foreignUuid('receiver_id');
            $table->timestamps();


        });
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('topic_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('sender_id');
            $table->text('content');
            $table->datetime('read_at')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
        Schema::dropIfExists('messages');
    }
};
