<?php

namespace App\Support;

/**
 * Utilidades para enlaces de YouTube.
 *
 * Fuente única del regex de extracción del ID de video: lo usan tanto
 * App\Models\Banner (accessors del slider) como App\Services\LiveStreamService
 * (derivar el embed del directo), para evitar duplicar el patrón.
 */
class YouTube
{
    /**
     * Extrae el ID de video (11 caracteres) de cualquier URL de YouTube
     * (watch, embed, shorts, youtu.be, v/). Devuelve null si no hay ID.
     */
    public static function extractId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $ok = preg_match(
            '/(?:youtube(?:-nocookie)?\.com\/(?:.*[?&]v=|embed\/|shorts\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/',
            $url,
            $m
        );

        return $ok ? $m[1] : null;
    }
}
