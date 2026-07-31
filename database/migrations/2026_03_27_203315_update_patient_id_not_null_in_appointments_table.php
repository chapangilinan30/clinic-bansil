<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Change patient_id to NOT NULL
            $table->unsignedBigInteger('patient_id')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Rollback: make it nullable again
            $table->unsignedBigInteger('patient_id')->nullable()->change();
        });
    }
};