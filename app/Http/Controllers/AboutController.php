<?php

namespace App\Http\Controllers;

use App\Services\VersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    protected VersionService $versionService;

    public function __construct(VersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    /**
     * Muestra la página de información del sistema, versión SemVer y stack técnico.
     * Si la petición solicita JSON (API o header Accept: application/json), retorna los metadatos en JSON.
     */
    public function index(Request $request): View|JsonResponse
    {
        $meta = $this->versionService->get();
        $version = $this->versionService->getVersion();
        $rawVersion = $this->versionService->getRawVersion();
        $shortCommit = $this->versionService->getShortCommit();
        $fullCommit = $this->versionService->getFullCommit();
        $commitUrl = $this->versionService->getCommitUrl();
        $branch = $this->versionService->getBranch();
        $commitDate = $this->versionService->getCommitDateFormatted();
        $gitHubRun = $this->versionService->getGitHubRunNumber();
        $systemInfo = $this->versionService->getSystemInfo();
        $changelog = $this->versionService->getChangelog();

        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'name'        => config('app.name', 'ECCA v4'),
                'version'     => $version,
                'semver'      => [
                    'major'      => $meta['major'] ?? 4,
                    'minor'      => $meta['minor'] ?? 8,
                    'patch'      => $meta['patch'] ?? 2,
                    'prerelease' => $meta['prerelease'] ?? null,
                ],
                'git'         => [
                    'branch'       => $branch,
                    'commit_short' => $shortCommit,
                    'commit_full'  => $fullCommit,
                    'commit_url'   => $commitUrl,
                    'commit_date'  => $commitDate,
                    'github_run'   => $gitHubRun,
                ],
                'system'      => $systemInfo,
                'changelog'   => $changelog,
            ]);
        }

        return view('about', compact(
            'meta',
            'version',
            'rawVersion',
            'shortCommit',
            'fullCommit',
            'commitUrl',
            'branch',
            'commitDate',
            'gitHubRun',
            'systemInfo',
            'changelog'
        ));
    }
}
