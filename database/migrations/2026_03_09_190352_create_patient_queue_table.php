<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('patient_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->enum('status', ['waiting', 'in_progress', 'done'])->default('waiting');
            $table->foreignId('clerk_id')->constrained('users');
            $table->integer('queue_number');
            $table->timestamp('scheduled_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('patient_queue');
    }
};