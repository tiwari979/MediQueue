<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_number', 20)->unique();
            $table->string('ward');
            $table->enum('bed_type', ['general', 'icu', 'special'])->default('general');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->timestamps();
            $table->index(['ward', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('beds'); }
};