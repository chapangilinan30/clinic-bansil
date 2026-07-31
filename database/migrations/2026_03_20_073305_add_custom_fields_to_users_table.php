<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Adding fields to match your Sign Up form
            $blueprint->string('surname')->after('name');
            $blueprint->string('mi', 2)->nullable()->after('surname');
            $blueprint->date('birthdate')->after('email');
            $blueprint->string('sex', 1)->after('birthdate'); // M or F
            $blueprint->string('contact')->after('sex');
            $blueprint->text('address')->after('contact');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['surname', 'mi', 'birthdate', 'sex', 'contact', 'address']);
        });
    }
};