<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLockoutFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_locked_from_booking')->default(false);
            $table->text('reactivation_reason')->nullable();
            $table->string('reactivation_status')->default('none'); // none, pending, approved
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_locked_from_booking', 'reactivation_reason', 'reactivation_status']);
        });
    }
}