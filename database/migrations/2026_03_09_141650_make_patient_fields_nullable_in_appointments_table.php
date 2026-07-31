<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->string('patient_email')->nullable()->change();
        $table->string('patient_phone')->nullable()->change();
        // REMOVE THIS: $table->foreignId('patient_id')->nullable()->change();
    });
}

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('patient_email')->nullable(false)->change();
            $table->string('patient_phone')->nullable(false)->change();
        });
    }
};