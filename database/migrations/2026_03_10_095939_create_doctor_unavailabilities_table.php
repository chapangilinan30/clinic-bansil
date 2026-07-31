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
    Schema::create('doctor_unavailabilities', function (Blueprint $table) {
        $table->id();
        // Links to your users table
        $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
        $table->date('date');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_unavailabilities');
    }
};