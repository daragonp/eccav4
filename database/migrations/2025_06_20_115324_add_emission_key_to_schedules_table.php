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
        if (!Schema::hasColumn('schedules', 'emission_key')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->string('emission_key')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('schedules', 'emission_key')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn('emission_key');
            });
        }
    }
};