<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->constrained('beds');
            $table->string('diagnosis');
            $table->string('doctor');
            $table->timestamp('admitted_at');
            $table->foreignId('admitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('discharged_at')->nullable();
            $table->enum('status', ['admitted', 'discharged', 'transferred'])->default('admitted');
            $table->text('discharge_summary')->nullable();
            $table->timestamps();
            $table->index(['patient_id', 'status']);
            $table->index('admitted_at');
        });
    }
    public function down(): void { Schema::dropIfExists('admissions'); }
};