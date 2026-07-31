<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_add_walkin_details_to_appointments_table.php

public function up()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->text('reason_for_visit')->nullable();
        $table->string('patient_type')->default('new'); // Stores 'new' or 'regular'
    });
}

public function down()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn(['reason_for_visit', 'patient_type']);
    });
}
};