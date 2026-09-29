<?php

namespace App\Console\Commands;

use App\Services\VersionService;
use Illuminate\Console\Command;

class VersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version 
                            {action? : Acción a realizar: info, patch, minor, major, set, sync-git} 
                            {value? : Nueva versión cuando se usa "set" o identificador de prerelease}
                            {--prerelease= : Etiqueta de prerelease opcional (ej: beta.1)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestiona e incrementa la versión SemVer del sistema ECCA v4 y sincroniza metadatos de Git/GitHub';

    /**
     * Execute the console command.
     */
    public function handle(VersionService $versionService): int
    {
        $action = strtolower(trim((string)$this->argument('action') ?: 'info'));
        $value = $this->argument('value');
        $prerelease = $this->option('prerelease') ?: ($action !== 'set' ? $value : null);

        switch ($action) {
            case 'patch':
            case 'minor':
            case 'major':
                $oldVersion = $versionService->getVersion();
                $newVersion = $versionService->bump($action, $prerelease);
                $this->info("✅ Versión incrementada ({$action}): {$oldVersion} ➜ v{$newVersion}");
                $this->line("📦 Build SHA: " . $versionService->getShortCommit() . " en rama " . $versionService->getBranch());
                return Command::SUCCESS;

            case 'set':
                if (empty($value)) {
                    $this->error("❌ Debes especificar la versión a establecer. Ejemplo: php artisan app:version set 4.9.0");
                    return Command::FAILURE;
                }
                $oldVersion = $versionService->getVersion();
                $newVersion = $versionService->setVersion($value);
                $this->info("✅ Versión establecida manualmente: {$oldVersion} ➜ v{$newVersion}");
                return Command::SUCCESS;

            case 'sync-git':
                $meta = $versionService->syncGitInfo();
                $this->info("✅ Metadatos de Git/GitHub sincronizados correctamente en version.json");
                $this->line("📌 Commit: {$meta['commit_short']} ({$meta['branch']}) • Fecha: {$meta['commit_date']}");
                return Command::SUCCESS;

            case 'info':
            default:
                $meta = $versionService->get();
                $sys = $versionService->getSystemInfo();

                $this->newLine();
                $this->line("<fg=green;options=bold>=========================================================</>");
                $this->line("<fg=yellow;options=bold>  ECCA v4 • Sistema de Versionado SemVer y Build Info   </>");
                $this->line("<fg=green;options=bold>=========================================================</>");
                $this->newLine();

                $this->table(
                    ['Propiedad', 'Valor'],
                    [
                        ['Versión SemVer', "<fg=green;options=bold>v{$meta['version']}</>"],
                        ['Rama Git', $meta['branch']],
                        ['Commit Hash Corto', $meta['commit_short'] ?: 'N/A'],
                        ['Commit Hash Completo', $meta['commit_hash'] ?: 'N/A'],
                        ['Fecha del Commit', $versionService->getCommitDateFormatted()],
                        ['GitHub Actions Run', $versionService->getGitHubRunNumber() ?: 'Local / Sin CI'],
                        ['Entorno Laravel', $sys['environment']],
                        ['Versión PHP', $sys['php_version']],
                        ['Versión Laravel', $sys['laravel_version']],
                        ['Driver de Base de Datos', $sys['database']],
                        ['Driver de Caché', $sys['cache']],
                    ]
                );

                $this->line("<fg=gray>Usa 'php artisan app:version [patch|minor|major]' para incrementar automáticamente.</>");
                $this->newLine();
                return Command::SUCCESS;
        }
    }
}
