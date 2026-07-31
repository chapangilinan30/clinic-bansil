<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            // Ensure each prescription_item belongs to a valid prescription
            $table->foreign('prescription_id')
                  ->references('id')
                  ->on('prescriptions')
                  ->onDelete('cascade');

            // Ensure each prescription_item references a valid medicine
            $table->foreign('medicine_id')
                  ->references('id')
                  ->on('medicines')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropForeign(['prescription_id']);
            $table->dropForeign(['medicine_id']);
        });
    }
};