<?php

namespace Database\Seeders;

use App\Models\LiveStream;
use Illuminate\Database\Seeder;

/**
 * Siembra la fila singleton de configuración de "Culto en Vivo".
 *
 * Delega en LiveStream::current(), que es idempotente: si la fila ya existe no
 * crea otra. Deja la configuración por defecto lista tras `migrate --seed`
 * (domingo 09:00–13:00, modo automático).
 */
class LiveStreamSeeder extends Seeder
{
    public function run(): void
    {
        LiveStream::current();
    }
}
