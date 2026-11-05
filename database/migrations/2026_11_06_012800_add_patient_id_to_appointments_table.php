<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('appointments', 'patient_id')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('patient_id')
                ->nullable()
                ->after('user_id')
                ->constrained('patients')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Keep this compatibility column to avoid dropping existing appointment links.
    }
};
