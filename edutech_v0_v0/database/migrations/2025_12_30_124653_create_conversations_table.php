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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete(); // Context: about which student
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Unique constraint: one conversation per teacher-parent-student combination
            $table->unique(['teacher_id', 'parent_id', 'student_id'], 'conversation_unique');

            // Indexes for efficient querying
            $table->index(['teacher_id', 'last_message_at']);
            $table->index(['parent_id', 'last_message_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
