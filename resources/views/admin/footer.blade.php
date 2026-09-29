@php
    $versionService = app(\App\Services\VersionService::class);
    $adminVersion = $versionService->getVersion();
    $adminCommit = $versionService->getShortCommit();
    $adminCommitUrl = $versionService->getCommitUrl();
@endphp
<footer class="bg-dark text-light py-4 border-top border-secondary">
    <div class="container text-center">
        <div class="row justify-content-center">
            <!-- Logo o nombre del sitio -->
            <div class="col-md-6">
                <h5 class="mb-2 text-white">Emancipación Cristiana Afro &bull; ECCA v4</h5>
                <div class="d-inline-flex align-items-center gap-2 mb-2">
                    <a href="{{ route('about') }}" class="badge bg-success text-decoration-none font-monospace" title="Ver notas de versión">
                        {{ $adminVersion }}
                    </a>
                    @if($adminCommitUrl)
                        <a href="{{ $adminCommitUrl }}" target="_blank" rel="noopener noreferrer" class="badge bg-secondary text-decoration-none font-monospace" title="Ver commit en GitHub">
                            commit: {{ $adminCommit }}
                        </a>
                    @else
                        <span class="badge bg-secondary font-monospace">{{ $adminCommit }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-2">
            <p class="mb-0 text-muted">
                <small>&copy; {{ date('Y') }} Todos los Derechos Reservados | <a href="{{ route('about') }}" class="text-light">Acerca del sistema</a> | <a href="https://www.tezbrillante.org" target="_blank" class="text-light">tezbrillante.org</a></small>
            </p>
        </div>
    </div>
</footer>
