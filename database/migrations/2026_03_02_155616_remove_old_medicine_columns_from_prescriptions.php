<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
            Schema::table('prescriptions', function (Blueprint $table) {

                // Check if column exists before dropping
                if (Schema::hasColumn('prescriptions', 'medicine_id')) {
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
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->text('instructions')->nullable();
        });
    }
};