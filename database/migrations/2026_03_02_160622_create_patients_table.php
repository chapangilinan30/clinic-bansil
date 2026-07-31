<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only create table if it doesn't exist
        if (!Schema::hasTable('patients')) {
            Schema::create('patients', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->date('birth_date');
                $table->string('gender');
                $table->string('contact_number')->nullable();
                $table->string('address')->nullable();
                $table->text('medical_history')->nullable();
                $table->foreignId('doctor_id')
                      ->constrained('users')
                      ->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if table exists
        if (Schema::hasTable('patients')) {
            Schema::dropIfExists('patients');
        }
    }
};