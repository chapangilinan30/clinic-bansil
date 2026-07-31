<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::table('prescriptions', function (Blueprint $table) {
        // Only add column if it doesn't exist
        if (!Schema::hasColumn('prescriptions', 'medicine_id')) {
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->onDelete('cascade');
        }
    });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['medicine_id']);
            $table->dropColumn('medicine_id');
        });
    }
};