<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiClient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'token',
        'token_prefix',
        'active',
        'last_used_at',
    ];

    protected $casts = [
        'active'       => 'boolean',
        'last_used_at' => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    /**
     * Registros de uso de este cliente.
     */
    public function requestLogs()
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    /**
     * Emite un nuevo cliente API con un token seguro.
     *
     * Genera el token en claro (prefijo 'ecca_' + 40 caracteres aleatorios), almacena
     * únicamente su hash sha256 y un prefijo identificable. Devuelve el cliente y el
     * token en claro, que debe mostrarse UNA sola vez al administrador y nunca se
     * vuelve a poder recuperar.
     *
     * @return array{client: \App\Models\ApiClient, plain: string}
     */
    public static function issue(string $name): array
    {
        $plain = 'ecca_' . Str::random(40);

        $client = static::create([
            'name'         => $name,
            'token'        => hash('sha256', $plain),
            'token_prefix' => substr($plain, 0, 9),
            'active'       => true,
        ]);

        return ['client' => $client, 'plain' => $plain];
    }
}
