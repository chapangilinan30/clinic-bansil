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
        // ✅ Only create table if it doesn't exist
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
        } else {
            // ✅ Table exists, add missing columns if needed
            Schema::table('patients', function (Blueprint $table) {
                if (!Schema::hasColumn('patients', 'first_name')) {
                    $table->string('first_name');
                }
                if (!Schema::hasColumn('patients', 'last_name')) {
                    $table->string('last_name');
                }
                if (!Schema::hasColumn('patients', 'birth_date')) {
                    $table->date('birth_date');
                }
                if (!Schema::hasColumn('patients', 'gender')) {
                    $table->string('gender');
                }
                if (!Schema::hasColumn('patients', 'contact_number')) {
                    $table->string('contact_number')->nullable();
                }
                if (!Schema::hasColumn('patients', 'address')) {
                    $table->string('address')->nullable();
                }
                if (!Schema::hasColumn('patients', 'medical_history')) {
                    $table->text('medical_history')->nullable();
                }
                if (!Schema::hasColumn('patients', 'doctor_id')) {
                    $table->foreignId('doctor_id')
                          ->constrained('users')
                          ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};