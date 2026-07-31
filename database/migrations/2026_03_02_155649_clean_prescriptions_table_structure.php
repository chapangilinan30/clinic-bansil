<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {

            // Drop foreign key safely
            if (Schema::hasColumn('prescriptions', 'medicine_id')) {

                // Try dropping foreign key only if column exists
                try {
                    $table->dropForeign(['medicine_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key does not exist
                }

                $table->dropColumn([
                    'medicine_id',
                    'dosage',
                    'frequency',
                    'duration',
                    'instructions',
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {

            $table->foreignId('medicine_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->text('instructions')->nullable();
        });
    }
};