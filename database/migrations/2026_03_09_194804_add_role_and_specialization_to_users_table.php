<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

        // ✅ Only add 'role' if it doesn't exist
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('patient');
        }

        // ✅ Only add 'specialization' if it doesn't exist
        if (!Schema::hasColumn('users', 'specialization')) {
            $table->string('specialization')->nullable();
        }
    });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'role')) {
            $table->dropColumn('role');
        }

        if (Schema::hasColumn('users', 'specialization')) {
            $table->dropColumn('specialization');
        }
    });
    }
};