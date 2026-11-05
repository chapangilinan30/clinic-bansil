<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('appointments', 'patient_id')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            // Change patient_id to NOT NULL
            $table->unsignedBigInteger('patient_id')->nullable(false)->change();
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('appointments', 'patient_id')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            // Rollback: make it nullable again
            $table->unsignedBigInteger('patient_id')->nullable()->change();
        });
    }
};