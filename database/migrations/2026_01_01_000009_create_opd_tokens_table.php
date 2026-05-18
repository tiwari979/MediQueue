<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('opd_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('department');
            $table->enum('priority', ['normal', 'senior', 'emergency'])->default('normal');
            $table->text('symptoms')->nullable();
            $table->unsignedInteger('token_number');
            $table->enum('status', ['waiting', 'in_consultation', 'served', 'cancelled'])->default('waiting');
            $table->unsignedInteger('estimated_wait')->default(0);
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('doctor_notes')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['department', 'status']);
            $table->index(['status', 'priority']);
        });
    }
    public function down(): void { Schema::dropIfExists('opd_tokens'); }
};