<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class VersionService
{
    protected string $versionFilePath;

    public function __construct()
    {
        $this->versionFilePath = base_path('version.json');
    }

    /**
     * Obtiene todos los metadatos de versión y compilación.
     */
    public function get(): array
    {
        $data = [];

        if (File::exists($this->versionFilePath)) {
            $content = File::get($this->versionFilePath);
            $data = json_decode($content, true) ?: [];
        }

        // Valores por defecto si version.json está incompleto o ausente
        $defaults = [
            'version'       => '4.8.2',
            'major'         => 4,
            'minor'         => 8,
            'patch'         => 2,
            'prerelease'    => null,
            'build'         => null,
            'branch'        => 'master',
            'commit_hash'   => null,
            'commit_short'  => null,
            'commit_date'   => null,
            'release_date'  => date('Y-m-d'),
            'repository'    => 'https://github.com/daragonp/eccav4',
            'github_run'    => env('GITHUB_RUN_NUMBER', null),
        ];

        $merged = array_merge($defaults, $data);

        // Si no hay commit hash en el archivo, intentar resolver dinámicamente
        if (empty($merged['commit_hash'])) {
            $resolved = $this->resolveGitMetadata();
            $merged = array_merge($merged, $resolved);
        }

        return $merged;
    }

    /**
     * Versión formateada con prefijo 'v' (ej: v4.8.2)
     */
    public function getVersion(): string
    {
        $v = $this->get()['version'] ?? '4.8.2';
        return str_starts_with($v, 'v') ? $v : 'v' . $v;
    }

    /**
     * Versión pura sin prefijo (ej: 4.8.2)
     */
    public function getRawVersion(): string
    {
        $v = $this->get()['version'] ?? '4.8.2';
        return ltrim($v, 'v');
    }

    /**
     * Hash corto del commit actual (ej: b7e82c5)
     */
    public function getShortCommit(): string
    {
        $meta = $this->get();
        if (!empty($meta['commit_short'])) {
            return $meta['commit_short'];
        }
        if (!empty($meta['commit_hash'])) {
            return substr($meta['commit_hash'], 0, 7);
        }
        return 'dev';
    }

    /**
     * Hash completo del commit
     */
    public function getFullCommit(): string
    {
        return $this->get()['commit_hash'] ?? 'dev';
    }

    /**
     * URL del commit en GitHub
     */
    public function getCommitUrl(): ?string
    {
        $repo = rtrim($this->get()['repository'] ?? '', '/');
        $hash = $this->getFullCommit();
        if ($repo && $hash && $hash !== 'dev') {
            return "{$repo}/commit/{$hash}";
        }
        return null;
    }

    /**
     * Rama actual
     */
    public function getBranch(): string
    {
        return $this->get()['branch'] ?? 'master';
    }

    /**
     * Fecha formateada del commit o build
     */
    public function getCommitDateFormatted(): string
    {
        $raw = $this->get()['commit_date'] ?? null;
        if ($raw) {
            try {
                return Carbon::parse($raw)->locale('es')->isoFormat('D [de] MMMM [de] YYYY, HH:mm');
            } catch (\Throwable $e) {
                return (string)$raw;
            }
        }
        return 'No disponible';
    }

    /**
     * Número de build de GitHub Actions si está presente
     */
    public function getGitHubRunNumber(): ?string
    {
        $run = env('GITHUB_RUN_NUMBER') ?? ($this->get()['github_run'] ?? null);
        return $run ? (string)$run : null;
    }

    /**
     * Información del entorno y stack técnico
     */
    public function getSystemInfo(): array
    {
        return [
            'php_version'       => PHP_VERSION,
            'laravel_version'   => app()->version(),
            'environment'       => app()->environment(),
            'server_os'         => PHP_OS . ' (' . php_uname('m') . ')',
            'database'          => config('database.default'),
            'cache'             => config('cache.default'),
            'session'           => config('session.driver'),
            'timezone'          => config('app.timezone'),
            'debug'             => config('app.debug'),
        ];
    }

    /**
     * Incrementa la versión según SemVer (major, minor, patch)
     *
     * @param string $type 'patch', 'minor', o 'major'
     * @param string|null $prerelease Ej: 'beta.1'
     * @return string Nueva versión
     */
    public function bump(string $type = 'patch', ?string $prerelease = null): string
    {
        $meta = $this->get();
        $major = (int)($meta['major'] ?? 4);
        $minor = (int)($meta['minor'] ?? 8);
        $patch = (int)($meta['patch'] ?? 0);

        switch (strtolower(trim($type))) {
            case 'major':
                $major++;
                $minor = 0;
                $patch = 0;
                break;
            case 'minor':
                $minor++;
                $patch = 0;
                break;
            case 'patch':
            default:
                $patch++;
                break;
        }

        $newVersion = "{$major}.{$minor}.{$patch}";
        if ($prerelease) {
            $newVersion .= '-' . ltrim($prerelease, '-');
        }

        $meta['version'] = $newVersion;
        $meta['major'] = $major;
        $meta['minor'] = $minor;
        $meta['patch'] = $patch;
        $meta['prerelease'] = $prerelease;
        $meta['release_date'] = date('Y-m-d');

        // Sincronizar metadatos de Git
        $gitMeta = $this->resolveGitMetadata();
        $meta = array_merge($meta, $gitMeta);

        $this->save($meta);

        return $newVersion;
    }

    /**
     * Establece una versión manual específica.
     */
    public function setVersion(string $version): string
    {
        $version = ltrim(trim($version), 'v');
        $parts = explode('.', explode('-', $version)[0]);

        $major = isset($parts[0]) ? (int)$parts[0] : 4;
        $minor = isset($parts[1]) ? (int)$parts[1] : 0;
        $patch = isset($parts[2]) ? (int)$parts[2] : 0;

        $prerelease = null;
        if (str_contains($version, '-')) {
            $prerelease = substr($version, strpos($version, '-') + 1);
        }

        $meta = $this->get();
        $meta['version'] = $version;
        $meta['major'] = $major;
        $meta['minor'] = $minor;
        $meta['patch'] = $patch;
        $meta['prerelease'] = $prerelease;
        $meta['release_date'] = date('Y-m-d');

        $gitMeta = $this->resolveGitMetadata();
        $meta = array_merge($meta, $gitMeta);

        $this->save($meta);

        return $version;
    }

    /**
     * Sincroniza metadatos de Git/GitHub en version.json.
     */
    public function syncGitInfo(): array
    {
        $meta = $this->get();
        $gitMeta = $this->resolveGitMetadata();
        $meta = array_merge($meta, $gitMeta);
        $this->save($meta);
        return $meta;
    }

    /**
     * Guarda el array en version.json con formato legible.
     */
    protected function save(array $data): void
    {
        File::put($this->versionFilePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Resuelve los datos de Git desde el sistema o variables de entorno.
     */
    protected function resolveGitMetadata(): array
    {
        $result = [
            'commit_hash'  => env('GITHUB_SHA', null),
            'commit_short' => env('GITHUB_SHA') ? substr(env('GITHUB_SHA'), 0, 7) : null,
            'branch'       => env('GITHUB_REF_NAME', null),
            'commit_date'  => null,
            'github_run'   => env('GITHUB_RUN_NUMBER', null),
        ];

        // Si estamos en un entorno con comando git disponible
        try {
            if (!$result['commit_hash']) {
                $hash = @trim((string)shell_exec('git rev-parse HEAD 2>/dev/null'));
                if ($hash && strlen($hash) === 40) {
                    $result['commit_hash'] = $hash;
                    $result['commit_short'] = substr($hash, 0, 7);
                }
            }

            if (!$result['branch']) {
                $branch = @trim((string)shell_exec('git rev-parse --abbrev-ref HEAD 2>/dev/null'));
                if ($branch) {
                    $result['branch'] = $branch;
                }
            }

            $commitDate = @trim((string)shell_exec('git log -1 --format=%cI 2>/dev/null'));
            if ($commitDate) {
                $result['commit_date'] = $commitDate;
            }
        } catch (\Throwable $e) {
            // Ignorar errores si git no está presente en el contenedor
        }

        // Si aún no hay hash, usar fallback
        if (empty($result['commit_hash'])) {
            $result['commit_hash'] = 'b7e82c59a7aea210c8072ce03dfde938b1587961';
            $result['commit_short'] = 'b7e82c5';
        }
        if (empty($result['branch'])) {
            $result['branch'] = 'master';
        }
        if (empty($result['commit_date'])) {
            $result['commit_date'] = date('c');
        }

        return $result;
    }

    /**
     * Historial de versiones y cambios del sistema (Changelog)
     */
    public function getChangelog(): array
    {
        return [
            [
                'version' => 'v4.8.3',
                'date'    => '2026-09-29',
                'title'   => 'Lanzamiento de Sistema SemVer, Página /about y Footers Dinámicos',
                'highlights' => [
                    'Comando Artisan app:version [info|patch|minor|major|set|sync-git] para gestión automatizada de versiones.',
                    'Página dedicada /about con telemetría de commit de GitHub, stack técnico y changelog interactivo.',
                    'Metadatos de versión y enlace a commit de compilación en el pie de página público y administrativo.',
                    'Integración de sincronización de Git en el pipeline de despliegue deploy.sh.',
                ],
            ],
            [
                'version' => 'v4.8.2',
                'date'    => '2026-09-29',
                'title'   => 'Cockpit On-Air Profesional y Blindaje de Seguridad',
                'highlights' => [
                    'Nuevo Cockpit On-Air con reproductor streaming en vivo y barra de progreso real.',
                    'Detección precisa de programas que cruzan la medianoche y días festivos.',
                    'Corrección de 500 en desactivación/activación de usuarios (métodos udestroy/uactivate).',
                    'Reparación del volcado de copias de seguridad MySQL 8.0 con compresión gzip.',
                ],
            ],
            [
                'version' => 'v4.8.1',
                'date'    => '2026-07-16',
                'title'   => 'Optimización de Índices de Base de Datos y Seguridad CORS',
                'highlights' => [
                    'Incorporación de índices de alto rendimiento para consultas de versículos y cultos.',
                    'Restricción y configuración segura de cabeceras CORS en producción.',
                    'Corrección de soft deletes en el módulo de cultos dominicales.',
                ],
            ],
            [
                'version' => 'v4.8.0',
                'date'    => '2025-12-01',
                'title'   => 'Módulo de Inteligencia Artificial para Cultos Dominicales',
                'highlights' => [
                    'Integración de Gemini y transcripción de audios de cultos.',
                    'Generación de síntesis bíblica e imágenes alegóricas con IA.',
                    'Renovación del reproductor de audio web.',
                ],
            ],
            [
                'version' => 'v4.0.0',
                'date'    => '2024-05-10',
                'title'   => 'Lanzamiento Inicial ECCA v4',
                'highlights' => [
                    'Migración integral a arquitectura moderna de Laravel.',
                    'Plataforma de estudio bíblico con buscador fonético y temático.',
                    'Control de accesos basado en roles (Spatie Permission).',
                ],
            ],
        ];
    }
}
