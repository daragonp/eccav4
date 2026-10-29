<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    use HasFactory;

    // Log inmutable: solo usamos created_at, asignado manualmente.
    public $timestamps = false;

    protected $fillable = [
        'api_client_id',
        'endpoint',
        'method',
        'ip',
        'user_agent',
        'status_code',
        'authorized',
        'created_at',
    ];

    protected $casts = [
        'authorized' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Cliente que originó la petición (null si no estaba autorizada).
     */
    public function client()
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }
}
