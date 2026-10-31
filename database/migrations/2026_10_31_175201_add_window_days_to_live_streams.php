<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade la columna JSON `window_days` para permitir múltiples días de
     * transmisión (ej: miércoles + domingos). Migra el dato existente de `window_day`
     * a un arreglo JSON `[window_day]` y luego elimina la columna `window_day`.
     */
    public function up(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            if (! Schema::hasColumn('live_streams', 'window_days')) {
                $table->json('window_days')->nullable()->after('instagram_url');
            }
        });

        // Migrar el valor existente si existe la columna legacy window_day
        if (Schema::hasColumn('live_streams', 'window_day')) {
            DB::table('live_streams')->get()->each(function ($row) {
                $existingDay = isset($row->window_day) ? (int) $row->window_day : 7;
                DB::table('live_streams')
                    ->where('id', $row->id)
                    ->update(['window_days' => json_encode([$existingDay])]);
            });

            Schema::table('live_streams', function (Blueprint $table) {
                $table->dropColumn('window_day');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            if (! Schema::hasColumn('live_streams', 'window_day')) {
                $table->unsignedTinyInteger('window_day')->default(7)->after('instagram_url');
            }
        });

        if (Schema::hasColumn('live_streams', 'window_days')) {
            DB::table('live_streams')->get()->each(function ($row) {
                $days = json_decode($row->window_days, true) ?? [7];
                DB::table('live_streams')
                    ->where('id', $row->id)
                    ->update(['window_day' => $days[0] ?? 7]);
            });

            Schema::table('live_streams', function (Blueprint $table) {
                $table->dropColumn('window_days');
            });
        }
    }
};

