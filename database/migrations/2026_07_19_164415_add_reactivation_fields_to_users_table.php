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
        Schema::table('users', function (Blueprint $table) {
            // Adds a text block column for the patient's explanation
            $table->text('reactivation_reason')->nullable()->after('is_locked_from_booking');
            
            // Adds a string column to track state ('pending', 'rejected', or null)
            $table->string('reactivation_status')->nullable()->after('reactivation_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reactivation_reason', 'reactivation_status']);
        });
    }
};