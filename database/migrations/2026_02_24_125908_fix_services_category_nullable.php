<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (Schema::hasColumn('services', 'category')) {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });
    }
}

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });
    }
};