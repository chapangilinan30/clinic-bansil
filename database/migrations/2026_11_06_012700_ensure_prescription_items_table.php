<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('prescription_items')) {
            return;
        }

        if (!Schema::hasTable('prescriptions') || !Schema::hasTable('medicines')) {
            throw new RuntimeException('Cannot create prescription_items before prescriptions and medicines exist.');
        }

        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // This migration can be a no-op on databases where prescription_items already exists.
    }
};
