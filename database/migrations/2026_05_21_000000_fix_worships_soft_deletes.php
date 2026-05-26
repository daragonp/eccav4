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
        Schema::table('worships', function (Blueprint $table) {
            if (Schema::hasColumn('worships', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worships', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->date('deleted_at')->nullable();
        });
    }
};
