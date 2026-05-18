<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique();
            $table->string('name');
            $table->date('dob');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('blood_group', ['A+','A-','B+','B-','O+','O-','AB+','AB-']);
            $table->string('phone', 15);
            $table->string('email')->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 15)->nullable();
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('phone');
            $table->index('name');
        });
    }
    public function down(): void { Schema::dropIfExists('patients'); }
};