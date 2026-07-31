<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prescriptions')) {

            Schema::create('prescriptions', function (Blueprint $table) {
                $table->id();

                $table->foreignId('patient_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('doctor_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

                $table->text('diagnosis')->nullable();

                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};