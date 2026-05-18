<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->enum('action', ['added', 'restocked', 'dispensed', 'adjusted', 'expired']);
            $table->unsignedInteger('quantity');
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('done_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['inventory_id', 'action']);
            $table->index('created_at');
        });
    }
    public function down(): void { Schema::dropIfExists('inventory_logs'); }
};