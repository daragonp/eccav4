# Plan de Implementación — Feature "Culto en Vivo"

Proyecto: ECCA v4 · Laravel 13.34 · Worktree `feat/culto-en-vivo`
Base: `diseno.md` (diseño aprobado, 2ª iteración) y `requisitos.md`. Este plan **secuencia** el diseño aprobado; no re-decide arquitectura.

## Contexto verificado (leído del worktree)

- **Rutas:** `routes/web.php` tiene el grupo admin `Route::group(['middleware' => ['auth','admin']], ...)` (con rutas estilo `/show-worship`, `/update-worship/{id}`) y, en la zona pública, `/api/programa-actual` (público) y `/worship`. `AdminController@nowPlaying` (bajo `['auth','admin']`) es el patrón proxy a replicar: `config('services.*')` + `Cache::remember(..., now()->addSeconds(..))` + `Http::withToken()->timeout(5)` + `response()->json($data, 200)`. `/live` es `HomeContentController@liveverse` (NO se toca).
- **Banner model:** `app/Models/Banner.php::detectYoutubeId()` es `protected` con el regex canónico `(?:youtube(?:-nocookie)?\.com\/(?:.*[?&]v=|embed\/|shorts\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})`.
- **config/services.php:** existe el bloque `widestream` (patrón a replicar para `youtube`). No hay bloque `youtube`.
- **Worships (migración real `2023_10_31_154704_create_worships_table.php`):** `title` tiene `->unique()`; `slug` NO; `abstract` (text), `badge` (text), `pdfdoc` (string) son NOT NULL sin default; `autor` string(30); `image` default `'generic.png'`; `audio/video/urlyt` nullable; `broadcast` date; soft delete vía columna `deleted_at`. (No hay columnas `ai_*` en la tabla real; no se escriben.)
- **Migraciones:** la última es `2026_06_15_000300_add_reference_and_verses_refs_to_verses_table.php`; la nueva usa `2026_06_16_000000` (se ordena al final).
- **Seeders:** `DatabaseSeeder::run()` llama a un `$this->call([...])` con array; se añade `LiveStreamSeeder::class` a ese array.
- **Tests:** PHPUnit (`phpunit.xml`), suites `tests/Unit` y `tests/Feature`, `use RefreshDatabase`, BD de test mysql `eccav4_testing` en host `laravel_db`. Se corren DENTRO del contenedor (`docker exec laravel_app php artisan test`). Patrón de admin autenticado en `tests/Feature/VerseSelectorTest.php` (crea `User` + rol spatie `administrador`).
- **Docker:** contenedor `laravel_app` (Laravel 13.34) monta la RAÍZ `/Users/daragonp/Proyectos/eccav4` como `/app`; publica `80 -> 0.0.0.0:8080`. El **worktree NO está montado**. BD compartida en `laravel_db`.

## Procedimiento de VERIFICACIÓN RUNTIME (obligatorio — worktree NO montado en Docker)

El worktree no está dentro del contenedor, así que para toda verificación en runtime hay que **sincronizar a la raíz montada, probar, y restaurar la raíz dejándola limpia**. Convención: `WT=/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo`, `ROOT=/Users/daragonp/Proyectos/eccav4`. **SHELL zsh: nunca usar la variable de bucle `path` (está reservada); usar `p`.**

Rutina de sincronización (ejecutar cada vez que se quiera probar en runtime los archivos nuevos/modificados):

1. **Copiar del worktree a la raíz** los archivos nuevos/modificados de este plan (crea directorios destino si faltan). Lista exacta de rutas relativas a sincronizar (las de este plan):
   `database/migrations/2026_06_16_000000_create_live_streams_table.php`, `app/Models/LiveStream.php`, `database/seeders/LiveStreamSeeder.php`, `database/seeders/DatabaseSeeder.php`, `app/Support/YouTube.php`, `app/Models/Banner.php`, `app/Services/LiveStreamService.php`, `app/Http/Controllers/LiveController.php`, `app/Http/Controllers/Admin/LiveSettingController.php`, `config/services.php`, `routes/web.php`, `resources/views/partials/live-banner.blade.php`, `resources/views/live/index.blade.php`, `resources/views/admin/live/index.blade.php`, `resources/views/welcome.blade.php`, `resources/views/layouts/menu.blade.php`, `resources/views/layouts/panel.blade.php`, `resources/views/admin/dashboard.blade.php`, `tests/Unit/LiveStreamServiceTest.php`, `tests/Feature/LiveStreamFeatureTest.php`, `.env.example`.
   Ejemplo zsh seguro (NO usar `path`):
   ```zsh
   WT=/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo
   ROOT=/Users/daragonp/Proyectos/eccav4
   for p in <rutas-relativas-de-arriba>; do
     mkdir -p "$ROOT/$(dirname "$p")" && cp "$WT/$p" "$ROOT/$p"
   done
   ```
   Registrar qué archivos eran **NUEVOS** en la raíz (no existían antes) frente a cuáles eran **trackeados modificados** — se restauran distinto en el paso 5.
2. **Migrar y limpiar caché** en el contenedor:
   ```zsh
   docker exec laravel_app php artisan migrate --force
   docker exec laravel_app php artisan optimize:clear
   ```
3. **Probar con curl desde el host** (código HTTP esperado entre paréntesis en cada paso del plan):
   ```zsh
   curl -s -o /dev/null -w "%{http_code}" http://localhost:8080<ruta>
   ```
4. **Probar el servicio con tinker** (tres modos; ver paso 5 del plan para el guion exacto):
   ```zsh
   docker exec laravel_app php artisan tinker
   ```
5. **RESTAURAR la raíz dejándola LIMPIA** — la versión final queda commiteada SOLO en el worktree:
   ```zsh
   ROOT=/Users/daragonp/Proyectos/eccav4
   cd "$ROOT"
   git checkout -- <rutas-trackeadas-modificadas>   # p.ej. routes/web.php, config/services.php, app/Models/Banner.php, resources/views/welcome.blade.php, layouts/menu.blade.php, layouts/panel.blade.php, admin/dashboard.blade.php, database/seeders/DatabaseSeeder.php, .env.example
   rm -f <rutas-nuevas-copiadas>                    # los archivos que no existían en la raíz
   git status --short                               # DEBE quedar sin cambios de la feature
   ```
   Las migraciones ya corrieron en la BD compartida `laravel_db`: **eso es correcto y se deja así** (la tabla `live_streams` y el singleton persisten en la BD; no se revierte la migración).
6. **Si `docker exec` falla por el socket** (Docker a veces se cae): esperar unos segundos y reintentar el mismo comando (`sleep 5` y repetir). No abortar al primer fallo de socket.

> Nota: los tests PHPUnit (`docker exec laravel_app php artisan test`) usan la BD `eccav4_testing` con `RefreshDatabase`, independiente de la verificación por curl/tinker; también requieren sincronizar los archivos a la raíz primero (mismo paso 1), y restaurar después (paso 5).

---

# Plan de Implementación

- [ ] 1. Crear la migración, el modelo `LiveStream` (singleton con `::current()`) y el seeder; registrar el seeder.
      Crear `database/migrations/2026_06_16_000000_create_live_streams_table.php` con la tabla `live_streams` y todas las columnas/defaults de §1 del diseño (`platform` default `'youtube'`; `youtube_channel_id/url`, `facebook_url`, `tiktok_url`, `x_url`, `instagram_url` nullable; `window_day` unsignedTinyInteger default 7; `window_start` time default `'09:00:00'`; `window_end` time default `'13:00:00'`; `mode` default `'auto'`; `archivar` boolean default false; `url_grabacion`, `title` nullable; `active` boolean default true; `timestamps()`). Crear `app/Models/LiveStream.php` con `$table='live_streams'`, `$fillable` y `$casts` de §1 y el estático `current(): self` usando `firstOrCreate([], [...defaults...])`. Crear `database/seeders/LiveStreamSeeder.php` que llame a `LiveStream::current()`. Añadir `LiveStreamSeeder::class` al array de `DatabaseSeeder::run()`.
      Archivos: `database/migrations/2026_06_16_000000_create_live_streams_table.php`, `app/Models/LiveStream.php`, `database/seeders/LiveStreamSeeder.php`, `database/seeders/DatabaseSeeder.php`.
      Verificar (runtime, rutina de sync §Procedimiento): tras `docker exec laravel_app php artisan migrate --force`, correr `docker exec laravel_app php artisan migrate:status` y confirmar que `2026_06_16_000000_create_live_streams_table` aparece como **Ran**. En tinker: `\App\Models\LiveStream::current()` devuelve una fila con `mode='auto'`, `window_day=7`, `window_start` que empieza en `09:00`, `active=true`; una segunda llamada NO crea otra fila (sigue habiendo 1 registro: `\App\Models\LiveStream::count() === 1`). (Criterio de aceptación 1.) Restaurar la raíz (dejar `DatabaseSeeder.php` limpio y quitar los archivos nuevos); la tabla/singleton quedan en la BD compartida, que es correcto.

- [ ] 2. Añadir la configuración `youtube` a `config/services.php` y documentar las variables en `.env.example`.
      Añadir el bloque `'youtube' => ['api_key' => env('YOUTUBE_API_KEY'), 'channel_id' => env('YOUTUBE_CHANNEL_ID')]` junto a `widestream` en `config/services.php`. Añadir `YOUTUBE_API_KEY=` y `YOUTUBE_CHANNEL_ID=` (vacías) a `.env.example` en el bloque de servicios. NO tocar el `.env` real (RNF-1).
      Archivos: `config/services.php`, `.env.example`.
      Verificar (runtime): tras sync + `php artisan optimize:clear`, en tinker `config('services.youtube')` devuelve un array con claves `api_key` y `channel_id` (ambas `null` sin env). Restaurar la raíz.

- [ ] 3. Crear el helper `App\Support\YouTube::extractId()` y refactorizar `Banner::detectYoutubeId()` para delegar en él (fuente única del regex).
      Crear `app/Support/YouTube.php` con `public static function extractId(?string $url): ?string` usando el regex canónico de §2 del diseño. Modificar `app/Models/Banner.php::detectYoutubeId()` para que haga `return \App\Support\YouTube::extractId($value);` (sin cambiar su firma `protected` ni el comportamiento de los accessors `*MediaEmbedUrl`).
      Archivos: `app/Support/YouTube.php`, `app/Models/Banner.php`.
      Verificar (runtime): en tinker `\App\Support\YouTube::extractId('https://youtu.be/dQw4w9WgXcQ')` devuelve `'dQw4w9WgXcQ'`, y `extractId('https://www.youtube.com/watch?v=dQw4w9WgXcQ')` igual; `extractId(null)` y `extractId('sin-id')` devuelven `null`. Restaurar la raíz. (Regresión del slider: el banner sigue derivando embeds igual, se confirma en la verificación global del paso 11.)

- [ ] 4. Crear `App\Services\LiveStreamService` con la lógica de precedencia, detección YouTube cacheada y la forma exacta de `getLiveState()`, más sus unit tests.
      Crear `app/Services/LiveStreamService.php` con `isLive(): bool` (delegando en `getLiveState()`) y `getLiveState(): array`. Implementar la precedencia de §2: `forzar_oculto`→false; `forzar_vivo`→true `source='manual'` (sin API); `auto`→ventana en `config('app.timezone')` normalizando extremos con `substr($valor,0,5)` e intervalo semiabierto `[inicio, fin)`; fuera de ventana→false `source='window'` sin llamar API; dentro de ventana con `$channelId = $live->youtube_channel_id ?: config('services.youtube.channel_id')` y `$apiKey = config('services.youtube.api_key')`: si `filled` ambos llama a YouTube (método `protected detectYouTubeLive()` aislado para test), si no→true `source='window'`. Fallback documentado en el código: dentro de ventana, cualquier no-confirmación de YouTube ⇒ `is_live=true source='window'`; solo confirmación en vivo ⇒ `source='youtube_api'` con embed `.../embed/{videoId}?autoplay=1`. Detección: `Http::timeout(5)->get('https://www.googleapis.com/youtube/v3/search', [...part=snippet,channelId,eventType=live,type=video,key,maxResults=1...])`, cachear resultado normalizado en `livestream:youtube_detect` TTL 3 min (fallo 30 s), `Log::warning` en excepción/HTTP no-2xx. `embed_url` según plataforma (youtube vía videoId o `YouTube::extractId($live->youtube_url)`; facebook vía plugin url; null si no hay). `watch_urls` copia columnas dejando null las vacías. Crear `tests/Unit/LiveStreamServiceTest.php` cubriendo las 8 filas de la tabla de estados con `Carbon::setTestNow()` y `Http::fake()` (incluye caso 8: fake 500/excepción ⇒ fallback window).
      Archivos: `app/Services/LiveStreamService.php`, `tests/Unit/LiveStreamServiceTest.php`.
      Verificar (runtime): (a) **unit tests** — tras sync, `docker exec laravel_app php artisan test --filter=LiveStreamServiceTest` pasa todos. (b) **tinker, tres modos** (sync + `optimize:clear` primero):
      - `forzar_oculto`: `$l=\App\Models\LiveStream::current(); $l->mode='forzar_oculto'; $l->save(); app(\App\Services\LiveStreamService::class)->isLive()` ⇒ `false`.
      - `forzar_vivo`: `$l->mode='forzar_vivo'; $l->youtube_url='https://youtu.be/dQw4w9WgXcQ'; $l->save(); $s=app(\App\Services\LiveStreamService::class)->getLiveState();` ⇒ `$s['is_live']===true` y `$s['embed_url']` no es null (embed de la plataforma principal).
      - `auto` fuera de ventana: `$l->mode='auto'; $l->window_day=1; $l->window_start='09:00:00'; $l->window_end='09:01:00'; $l->save();` con la hora actual fuera ⇒ `isLive()===false` y, confirmando con `Http::fake()` en un test (no en tinker), **no se llama** a la API.
      - `auto` dentro de ventana sin key: fijar ventana al día/hora actual, dejar `YOUTUBE_API_KEY`/`channel_id` vacíos ⇒ `getLiveState()` ⇒ `is_live=true`, `source='window'`, sin excepción.
      Restaurar la raíz. (Criterios 2, 3, 4, 5.)

- [ ] 5. Crear `LiveController` (público) con `index` y `status`, y registrar las rutas públicas `/en-vivo` y `/api/live-status`.
      Crear `app/Http/Controllers/LiveController.php`: `index()` ⇒ `view('live.index', ['state' => app(LiveStreamService::class)->getLiveState()])`; `status()` ⇒ `Cache::remember('livestream:status', now()->addMinutes(3), fn() => app(LiveStreamService::class)->getLiveState())` y `response()->json($data, 200)` (key nunca expuesta). En `routes/web.php`, zona pública (fuera del grupo `['auth','admin']`, cerca de `/api/programa-actual` y `/worship`): `Route::get('/en-vivo', [LiveController::class, 'index'])->name('live.index')` y `Route::get('/api/live-status', [LiveController::class, 'status'])->name('api.live.status')`. Importar la clase. NO tocar `/live`.
      Archivos: `app/Http/Controllers/LiveController.php`, `routes/web.php`.
      Verificar (runtime): tras sync + `optimize:clear`, `curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/api/live-status` ⇒ **200**; `curl -s http://localhost:8080/api/live-status` devuelve JSON con las claves `is_live, platform, embed_url, watch_urls, title, source`. `curl ... /en-vivo` ⇒ **200**. Restaurar la raíz. (Criterios 6, 7 parcial.)

- [ ] 6. Crear el partial del banner, la vista pública `/en-vivo`, incluir el banner en `welcome.blade.php` y añadir el enlace "En Vivo" al menú público.
      Crear `resources/views/partials/live-banner.blade.php`: contenedor oculto (`x-data="{live:false,state:null}"`, `x-show="live"`), `fetch('/api/live-status')` en `x-init` + `setInterval` 60s, se muestra solo si `state.is_live===true`; franja emerald con pulso (`bg-emerald-500 animate-pulse`, estilo del indicador de `layouts/panel.blade.php`), texto "EN VIVO AHORA — Culto Dominical" y botón "Ver ahora" a `route('live.index')`. Crear `resources/views/live/index.blade.php` (`@extends('layouts.main')`): con `$state['is_live']` true renderiza iframe responsivo 16:9 de altura fija (coherente con `player.blade.php`) usando `$state['embed_url']`, y debajo botones externos `target="_blank" rel="noopener"` solo para `watch_urls` no nulas (Facebook siempre muestra también enlace externo); con false muestra "No hay transmisión en vivo en este momento" + enlace opcional a `url_grabacion`/`/worship`. Modificar `resources/views/welcome.blade.php` para `@include('partials.live-banner')` al inicio de `@section('content')`. Modificar `resources/views/layouts/menu.blade.php` añadiendo el enlace "En Vivo" (escritorio con `nav-link`/`nav-link-active`, móvil con `mobile-nav-link`/`mobile-nav-link-active`, apuntando a `url('en-vivo')`).
      Archivos: `resources/views/partials/live-banner.blade.php`, `resources/views/live/index.blade.php`, `resources/views/welcome.blade.php`, `resources/views/layouts/menu.blade.php`.
      Verificar (runtime): tras sync + `optimize:clear`, `curl ... /` ⇒ **200** y `curl -s http://localhost:8080/ | grep -i "live-status\|EN VIVO"` encuentra el fetch/el marcado del banner. `curl ... /en-vivo` ⇒ **200** y el HTML contiene el enlace "En Vivo" en el menú y el bloque de estado (iframe o mensaje "No hay transmisión..."). Restaurar la raíz. (Criterios 7, 8.)

- [ ] 7. Crear `Admin\LiveSettingController` (`show`, `update`), la vista de ajustes y registrar las rutas admin de ajuste/guardado con validación en español.
      Crear `app/Http/Controllers/Admin/LiveSettingController.php` con `show()` ⇒ `view('admin.live.index', ['live' => LiveStream::current()])` y `update(Request $request)` con la validación en español de §5 (incluye `window_start`/`window_end` `date_format:H:i`, `window_end` `after:window_start`, `mode` `in:auto,forzar_vivo,forzar_oculto`), normalizando `archivar` con `$request->boolean('archivar')` y guardando sobre `LiveStream::current()`, redirige `->back()->with('success', ...)`. Crear `resources/views/admin/live/index.blade.php` (`@extends('layouts.panel')`) con un único form `POST` a `route('live.admin.update')` + `@csrf` editando todos los campos de §5. En `routes/web.php`, dentro del grupo `['auth','admin']`: `GET /show-live` (`live.admin.show`) y `POST /update-live` (`live.admin.update`). Importar el controlador.
      Archivos: `app/Http/Controllers/Admin/LiveSettingController.php`, `resources/views/admin/live/index.blade.php`, `routes/web.php`.
      Verificar (runtime): tras sync + `optimize:clear`, `curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/show-live` ⇒ **302** (redirección a login sin auth, nunca 500). La persistencia autenticada y la validación se cubren por **feature test** en el paso 10 (`docker exec laravel_app php artisan test --filter=LiveStreamFeatureTest`). Restaurar la raíz. (Criterio 9 parcial.)

- [ ] 8. Añadir el botón de override de un clic (`toggleLive`) a `LiveSettingController`, su ruta, el botón en la vista de ajustes, la tarjeta en el dashboard y el enlace en el menú del panel.
      Añadir `toggleLive()` a `Admin\LiveSettingController`: si `mode !== 'forzar_vivo'` ⇒ `forzar_vivo`, si no ⇒ `auto`; tras guardar `Cache::forget('livestream:status'); Cache::forget('livestream:youtube_detect');` y `->back()->with('success', ...)`. Registrar `POST /toggle-live` (`live.admin.toggle`) en el grupo admin. Añadir el form `POST` (con `@csrf`) del botón de override en `resources/views/admin/live/index.blade.php` y una tarjeta/botón equivalente en `resources/views/admin/dashboard.blade.php`. Añadir el enlace "Culto en Vivo" en `resources/views/layouts/panel.blade.php` dentro del nav-group "Radio & Emisión", apuntando a `url('show-live')` con marcado activo `request()->is('show-live*')`.
      Archivos: `app/Http/Controllers/Admin/LiveSettingController.php`, `routes/web.php`, `resources/views/admin/live/index.blade.php`, `resources/views/admin/dashboard.blade.php`, `resources/views/layouts/panel.blade.php`.
      Verificar (runtime): `curl ... /show-live` sigue ⇒ **302** sin auth. El cambio de modo por `POST /toggle-live` (alterna `forzar_vivo`↔`auto` y modifica `embed_url`/enlaces del estado) se cubre por **feature test** (paso 10). Confirmar en el HTML autenticado (o en el test) que los enlaces "En Vivo" (menú público, paso 6) y "Culto en Vivo" (panel) aparecen. Restaurar la raíz. (Criterio 9.)

- [ ] 9. Implementar el archivado: acción `archive()` en `LiveSettingController`, su ruta, el botón "Archivar como culto" y el feature test de archivado.
      Añadir `archive()` a `Admin\LiveSettingController` siguiendo §6 al detalle: exige `url_grabacion` (si falta ⇒ `->back()->withErrors(['url_grabacion' => '...'])`); calcula el último domingo ≤ hoy en `config('app.timezone')`; desambigua el **`title`** (columna UNIQUE) con el bucle `while (Worship::withTrashed()->where('title',$title)->where('broadcast','!=',$fecha)->exists())` y restaura la fila soft-deleted del mismo domingo si existe; `Worship::withTrashed()->updateOrCreate(['broadcast'=>$fecha], ['title'=>$title,'slug'=>Str::slug($title),'abstract'=>'','badge'=>'Culto Dominical','pdfdoc'=>'','autor'=>'Culto en Vivo','video'=>$live->url_grabacion,'urlyt'=>$live->url_grabacion])`; redirige `->back()->with('success', ...)`. Registrar `POST /archive-live` (`live.admin.archive`) en el grupo admin. Añadir el botón "Archivar como culto" (form `POST` + `@csrf`) en `resources/views/admin/live/index.blade.php`. Añadir los casos de archivado al feature test (crea Worship con columnas NOT NULL pobladas, idempotencia por `broadcast`, desambiguación de `title` con sufijo ` (2)`).
      Archivos: `app/Http/Controllers/Admin/LiveSettingController.php`, `routes/web.php`, `resources/views/admin/live/index.blade.php`, `tests/Feature/LiveStreamFeatureTest.php`.
      Verificar (runtime): tras sync, `docker exec laravel_app php artisan test --filter=LiveStreamFeatureTest` pasa; en particular el test de archivado crea un `Worship` con `broadcast`=domingo, `video`/`urlyt`=URL, `abstract`/`badge`/`pdfdoc` no nulos, y un segundo POST del mismo domingo no duplica. Restaurar la raíz. (Criterio 10.)

- [ ] 10. Crear/completar el feature test `LiveStreamFeatureTest` que cubre endpoint, página, home, panel protegido, persistencia, override y archivado.
      Crear/ampliar `tests/Feature/LiveStreamFeatureTest.php` (`use RefreshDatabase`, admin autenticado con rol spatie `administrador` como en `VerseSelectorTest`): `GET /api/live-status` ⇒ 200 + claves del contrato; `GET /en-vivo` ⇒ 200 (con servicio forzado `is_live=true` el HTML tiene iframe; con false, el mensaje); `GET /` ⇒ 200 e incluye el partial del banner; `GET /show-live` sin auth ⇒ 302; autenticado ⇒ 200; `POST /update-live` válido persiste en `LiveStream::current()`, inválido ⇒ 302 con errores; `POST /toggle-live` alterna `mode`; casos de archivado (del paso 9). (Si el paso 9 ya dejó el archivo creado, aquí se completan los casos restantes; es el mismo archivo.)
      Archivos: `tests/Feature/LiveStreamFeatureTest.php`.
      Verificar (runtime): tras sync, `docker exec laravel_app php artisan test --filter=LiveStreamFeatureTest` y `--filter=LiveStreamServiceTest` pasan todos. Restaurar la raíz.

- [ ] 11. Redactar `GUIA_TRANSMISION.md` en español y ejecutar la verificación global + regresión.
      Crear `GUIA_TRANSMISION.md` en la raíz del worktree con las 12 secciones del índice de §7 (introducción, requisitos, stream key RTMP de YouTube, OBS, dos cámaras, audio, escena básica, ajustes OBS, flujo del domingo, obtener YouTube Data API KEY en Google Cloud + dónde ponerla, transmitir por Facebook/otra red con el botón de override, solución de problemas), en español y dirigido a persona no técnica.
      Archivos: `GUIA_TRANSMISION.md`.
      Verificar (runtime, verificación global tras sync + `migrate --force` + `optimize:clear`): con `curl -s -o /dev/null -w "%{http_code}" http://localhost:8080<ruta>` confirmar **200** en `/`, `/about`, `/biblia`, `/worship`, `/donate`, `/library`, `/en-vivo`; confirmar **401** en la API privada del versículo sin token: `curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/api/v1/verse/today` (ruta real bajo `auth.apiclient` en `routes/api.php`). Confirmar que el dock/reproductor de WideStream (`resources/views/.../player.blade.php`) y el modal universal del panel siguen intactos (no se modificaron). Correr toda la suite: `docker exec laravel_app php artisan test` (sin regresiones). Confirmar que `GUIA_TRANSMISION.md` existe y está en español. **Restaurar la raíz dejándola limpia** (git checkout de trackeados modificados + rm de nuevos; `git status --short` sin cambios de la feature). (Criterios 11, 12.)

- [ ] 12. Agrupar los commits lógicamente en `feat/culto-en-vivo` con mensajes en español.
      Hacer commits SOLO en el worktree (nunca push), agrupados así: (1) migración + modelo + seeder + registro; (2) config youtube + `.env.example` + helper `YouTube` + refactor `Banner` + `LiveStreamService` + unit tests (detección YouTube + servicio); (3) `LiveController` + rutas públicas + banner + página `/en-vivo` + enlace menú (banner+página+menú); (4) `Admin\LiveSettingController` (show/update) + vista + rutas admin + override/toggle + tarjeta dashboard + enlace panel (panel admin + override); (5) archivado (`archive()` + ruta + botón + feature test); (6) `GUIA_TRANSMISION.md` (documento). Agrupar el paso 2 del plan (config youtube) con el commit (2) y el helper/servicio; los tests de feature pueden ir con su commit funcional correspondiente.
      Archivos: todos los del worktree (solo commits locales).
      Verificar: `git -C /Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo log --oneline` muestra los commits agrupados con mensajes en español; `git -C ... status` del worktree limpio. (No se hace push; RNF-5.)

---

## Notas y supuestos

- **Decisión de no descomponer en FEATs:** el trabajo es una sola feature cohesiva con cadena de dependencias estricta (modelo → servicio → endpoints → UI → admin → archivado → doc); cada parte depende de la anterior y no hay unidades independientemente desplegables. Se entrega como plan único para el bucle de implementación/revisión existente.
- **API privada del versículo (paso 11, esperado 401):** la ruta real es `GET /api/v1/verse/today` (`routes/api.php`, grupo `v1` con middleware `auth.apiclient`); sin token/cliente API debe responder 401.
- **Restauración de la raíz:** imprescindible tras CADA verificación runtime. La migración en `laravel_db` se deja aplicada a propósito; lo que debe quedar limpio es el árbol de archivos de la raíz (`git status --short` sin cambios de la feature).
- **zsh:** en los bucles de copia/restauración usar `p` como variable, nunca `path`.
- Si Docker se cae durante la verificación, reintentar `docker exec` tras `sleep 5`.

**Ruta del documento:** `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/.agents/tasks/plan.md`
