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
            if (!Schema::hasColumn('users', 'reactivation_reason')) {
                $table->text('reactivation_reason')->nullable()->after('is_locked_from_booking');
            }

            if (!Schema::hasColumn('users', 'reactivation_status')) {
                $table->string('reactivation_status')->nullable()->after('reactivation_reason');
            }
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