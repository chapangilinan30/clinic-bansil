<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Only create table if it doesn't exist
        if (!Schema::hasTable('patient_notes')) {

            Schema::create('patient_notes', function (Blueprint $table) {
                $table->id();

                $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
                $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();

                $table->string('diagnosis');
                $table->text('notes')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_notes');
    }
};