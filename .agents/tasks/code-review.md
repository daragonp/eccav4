# Revisión de código — Feature "Culto en Vivo"

Rama: `feat/culto-en-vivo` · Base: `master` · Worktree: `.worktrees/culto-en-vivo`
Veredicto: **APPROVED** — sin hallazgos bloqueantes.

## Alcance revisado

- Diff `git diff master...feat/culto-en-vivo` (23 archivos, +1735/-6).
- Contraste contra `requisitos.md`, `diseno.md` (2ª iteración) y `plan.md`.
- Evidencia de verificación runtime del coder: `.agents/tasks/verificacion-runtime.md`
  y mensajes de commit (5 commits agrupados lógicamente).
- Lectura de archivos sin cambios relevantes para confirmar consecuencias
  (rutas públicas/admin, ausencia de `database/factories/UserFactory`).

## Evidencia de verificación (leída del registro del coder)

El coder ejecutó la verificación runtime siguiendo el procedimiento del plan
(sincronizar a la raíz montada en Docker, probar, restaurar la raíz). Resultados
registrados:

- **Migración:** `2026_06_16_000000_create_live_streams_table` → `Ran` (`migrate:status`).
- **Singleton:** `LiveStream::current()` → `mode=auto day=7 start=09:00:00 count=1`;
  segunda llamada no duplica (`count=1`).
- **Servicio, tres modos (tinker):** `forzar_oculto`→false; `forzar_vivo`→true con
  `embed=.../embed/dQw4w9WgXcQ?autoplay=1`; `auto` fuera de ventana→false sin API;
  `auto` dentro sin key→true `source=window` sin excepción.
- **Endpoints (curl):** `/api/live-status`→200 JSON con el contrato;
  `/en-vivo`→200 (iframe con `forzar_vivo`, mensaje "No hay transmisión..." con
  `forzar_oculto`); `/`→200 con el fetch del banner; `/show-live` sin auth→302.
- **Regresión (curl):** `/`, `/about`, `/biblia`, `/worship`, `/donate`, `/library`,
  `/en-vivo`→200; `/api/v1/verse/today` sin token→401. Dock de WideStream y modal
  universal del panel no modificados.
- **Tests:** `phpunit --filter 'LiveStreamServiceTest|LiveStreamFeatureTest'` → OK
  (23 tests, 67 asserts). Suite completa: 95 tests, 11 errores + 2 fallos, todos en
  `tests/Feature/Auth/*`.
- **Raíz restaurada:** `git status --short` limpio (solo `.agents/` y `.worktrees/`).

Spot-check de la única duda articulable (que los 13 fallos de la suite completa sean
pre-existentes y ajenos a la feature): confirmado sin re-ejecutar suites — **no existe
`database/factories/` ni `UserFactory`** en el worktree, y los 5 tests de
`tests/Feature/Auth/*` (scaffolding Breeze) dependen de `User::factory()`, por lo que
fallan con o sin este cambio. No tocan ningún archivo de la feature. No es regresión.

La evidencia requerida está presente y es coherente; no se re-ejecutaron suites ni curls.

## Comprobación por requisito / criterio de aceptación

1. **Modelo singleton + migración (RF-1, AC-1):** cumple. Migración con timestamp
   `2026_06_16_000000`, posterior a la última real (`2026_06_15_000300`), se ordena al
   final. Columnas, defaults y casts según §1 del diseño. `LiveStream::current()` usa
   `firstOrCreate` idempotente; `LiveStreamSeeder` registrado en `DatabaseSeeder`.
2. **Servicio tres modos + tabla de estados (RF-2, AC-2/3/4/5):** cumple. Precedencia
   `forzar_oculto`→false / `forzar_vivo`→true `manual` (sin API) / `auto`→ventana. Fuera
   de ventana no llama a la API. Ventana normalizada a `HH:MM` con `substr(...,0,5)` e
   intervalo semiabierto `[inicio, fin)`. Degradación sin key/canal resuelve por ventana
   sin excepción. Las 8 filas de la tabla más los bordes 09:00/13:00 están cubiertos por
   `LiveStreamServiceTest`.
3. **Endpoint `/api/live-status` (RF-3, AC-6):** cumple. `LiveController@status` replica
   el patrón `nowPlaying`: `Cache::remember('livestream:status', 3 min, ...)` +
   `response()->json($data, 200)`. La key se lee solo vía `config('services.youtube.*')`
   y nunca aparece en la respuesta (el estado serializado no la incluye). Detección con
   `search.list eventType=live`, `timeout(5)`, caché `livestream:youtube_detect` 3 min
   (30 s en fallo), `Log::warning` en error. Key de YouTube protegida: `.env.example`
   trae `YOUTUBE_API_KEY=`/`YOUTUBE_CHANNEL_ID=` vacías; no hay valores reales commiteados.
4. **Banner en home (RF-4, AC-8):** cumple. `partials/live-banner.blade.php` incluido al
   inicio de `@section('content')` de `welcome.blade.php`; `x-show="live"` con `live:false`
   por defecto, `fetch('/api/live-status')` en `x-init` + `setInterval` 60 s; `x-cloak`
   respaldado por regla global `[x-cloak]{display:none}` en `resources/css/tw.css`.
5. **Página `/en-vivo` (RF-5, AC-7):** cumple. Ruta pública `live.index`, iframe 16:9 con
   altura fija (`aspect-ratio:16/9; min-height:360px`), sin tapar contenido; enlaces
   externos solo para `watch_urls` no nulas; estado sin directo con el texto exacto y
   enlace a `/worship`.
6. **Menús (RF-6, RF-7.5):** cumple. Enlace "En Vivo" en escritorio y móvil de
   `layouts/menu.blade.php`; enlace "Culto en Vivo" en `layouts/panel.blade.php`.
7. **Panel admin (RF-7, AC-9):** cumple. Rutas bajo `['auth','admin']`; `show-live` sin
   auth→302. Formulario único que persiste sobre `LiveStream::current()`; validación en
   español por campo (incluye `window_end after:window_start`). `toggleLive` alterna
   `forzar_vivo`↔`auto` e invalida cachés. Tarjeta de override en dashboard.
8. **Archivado (RF-8, AC-10):** cumple. `archive()` exige `url_grabacion`, calcula el
   último domingo, desambigua el `title` UNIQUE contando soft-deleted, restaura fila del
   mismo domingo si existe y hace `updateOrCreate(['broadcast'=>...])` poblando columnas
   NOT NULL (`abstract`, `badge`, `pdfdoc`, `autor`). Idempotente por `broadcast`. Cubierto
   por feature tests (creación, idempotencia, desambiguación `(2)`).
9. **Guía (RF-9, AC-11):** cumple. `GUIA_TRANSMISION.md` en español, 12 secciones, para
   persona no técnica (OBS, doble cámara, audio, API key en Google Cloud, override para
   Facebook, solución de problemas).
10. **Sin regresiones (RNF-2, AC-12):** cumple. El diff no toca `/live` (liveverse),
    API del versículo, slider, biblioteca, dock de WideStream (`player.blade.php`) ni el
    modal universal. `Banner::detectYoutubeId()` delega en `YouTube::extractId()`
    manteniendo el mismo regex y comportamiento de los accessors. Regresión confirmada
    por los curls y la suite dirigida del coder.
11. **Commits en español agrupados (RNF-5):** cumple. 5 commits: migración/modelo/seeder,
    servicio+config+helper+tests, endpoint/página/banner/menú, panel/override/archivado,
    guía+nota de verificación. Sin push.
12. **Raíz limpia:** cumple. `git status` de `/Users/daragonp/Proyectos/eccav4` sin
    cambios de la feature (solo `.agents/` y `.worktrees/` preexistentes).

## Observaciones no bloqueantes (no requieren acción)

- `x-cloak` del banner: la regla global existe en `tw.css`/`dashboard.css`, y además
  `x-show` con `live:false` ya evita mostrarlo; no hay parpadeo.
- Embed de Facebook: tratado como iframe simple con enlace externo de respaldo, tal como
  decide el diseño (§4, hallazgo 8). Es un trade-off aprobado, no un defecto.

## Conclusión

La implementación cumple los requisitos y la verificación de éxito del usuario, respeta
la tabla de estados del diseño, protege la API key y no introduce regresiones. La
evidencia de verificación runtime está completa en el registro del coder. No quedan
hallazgos bloqueantes.
