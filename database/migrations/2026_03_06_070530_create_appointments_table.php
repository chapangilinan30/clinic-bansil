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
        if (!Schema::hasTable('appointments')) {

            Schema::create('appointments', function (Blueprint $table) {
                $table->id();

                // Link sa Patient Account (nullable for Walk-ins)
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();

                // --- KONEKSYON FIELDS ---
                $table->string('doctor_id'); 
                $table->string('doctor_name'); 
                $table->string('department');

                // --- QUEUE LOGIC ---
                $table->date('appointment_date');
                $table->string('appointment_time');
                $table->integer('queue_number'); // For ordering

                // --- PATIENT INFO ---
                $table->string('patient_name');
                $table->string('patient_email')->nullable(); 
                $table->string('patient_phone')->nullable(); 
                $table->string('purpose')->default('Consultation');
                $table->text('notes')->nullable();

                // --- STATUS FLOW ---
                $table->string('status')->default('waiting'); 
                $table->boolean('is_walk_in')->default(false);

                // --- SESSION DATA ---
                $table->text('diagnosis')->nullable();
                $table->text('medicine')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};