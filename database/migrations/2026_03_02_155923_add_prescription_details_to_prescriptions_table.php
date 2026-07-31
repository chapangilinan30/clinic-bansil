<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('prescriptions', function (Blueprint $table) {

        if (!Schema::hasColumn('prescriptions', 'dosage')) {
            $table->string('dosage')->nullable();
        }

        if (!Schema::hasColumn('prescriptions', 'frequency')) {
            $table->string('frequency')->nullable();
        }

        if (!Schema::hasColumn('prescriptions', 'duration')) {
            $table->string('duration')->nullable();
        }

        if (!Schema::hasColumn('prescriptions', 'instructions')) {
            $table->text('instructions')->nullable();
        }

    });
}
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'dosage',
                'frequency',
                'duration',
                'instructions'
            ]);
        });
    }
};
