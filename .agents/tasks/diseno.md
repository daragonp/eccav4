# Diseño Técnico — Feature "Culto en Vivo"

Proyecto: ECCA v4 (Iglesia Emancipación Cristiana Afro) · Laravel · Worktree `feat/culto-en-vivo`
Documento de diseño basado en `requisitos.md`. **Segunda iteración**: incorpora las correcciones de la revisión `design-review.md` (veredicto `CHANGES_REQUESTED`: 2 HIGH, 3 MEDIUM, 3 NIT). Las respuestas a cada hallazgo están al final del documento (§ "Respuestas a la revisión de diseño").

## Visión general

La feature añade una sección "Culto en Vivo" que se activa automáticamente los domingos durante la ventana del culto y, de forma asistida, cuando el operador lo fuerce. El corazón es un **singleton en base de datos** (`LiveStream`) con la configuración de la transmisión (plataformas, ventana horaria, modo de visibilidad, archivado) y un **servicio de dominio** (`App\Services\LiveStreamService`) que resuelve la pregunta "¿estamos en vivo ahora?" combinando tres señales en este orden de precedencia: **modo/override manual → ventana horaria → detección por YouTube Data API v3**. El front-end nunca habla con YouTube ni ve la API key: consume un **endpoint proxy server-side `GET /api/live-status`** (cacheado) que **replica el mecanismo server-side de `AdminController@nowPlaying`** (proxy de WideStream: `Http` + `Cache::remember` + `response()->json(..., 200)` con la key solo en servidor), pero **expuesto como ruta pública** porque lo consume el visitante anónimo (a diferencia de `nowPlaying`, que vive bajo `['auth','admin']`). El modelo de visibilidad correcto es el de `/api/programa-actual`, que ya es público en el repo. Sobre ese endpoint se montan el **banner condicional en la home**, la **página pública `/en-vivo`** con reproductor embebido, y un **panel de administración** (una sola vista de ajustes de singleton, no CRUD de lista) con un **botón de override de un clic**. Finalmente, un **archivado asistido** crea un registro `Worship` a partir de la grabación, y un **documento de operación** `GUIA_TRANSMISION.md` guía a la persona no técnica que maneja OBS.

El stack queda fijado así (bloqueado tras aprobación): **Laravel (Eloquent + Blade + rutas web), Carbon para la zona horaria, `Illuminate\Support\Facades\Http` para la llamada a YouTube, `Cache` (driver por defecto del proyecto) para cachear la detección y la respuesta del proxy, Tailwind de marca con Alpine.js y `fetch` del navegador en el lado cliente.** No se introducen librerías nuevas; todo son componentes ya presentes en el repo (se verificó `Http` y `Cache` en `AdminController@nowPlaying`).

La decisión de dominio que atraviesa todo: **solo YouTube se autodetecta**. Facebook, TikTok, X e Instagram no tienen API fiable de "¿estoy emitiendo?", así que se rigen por ventana horaria + override manual y se ofrecen como enlaces externos (y Facebook, opcionalmente, como embed de la plataforma principal).

---

## 1. Modelo de datos (singleton)

### Modelo y tabla

- **Modelo:** `App\Models\LiveStream`
- **Tabla:** `live_streams`
- **Migración:** `database/migrations/2026_06_16_000000_create_live_streams_table.php`
  - El timestamp `2026_06_16_000000` es **posterior** a la última migración real verificada (`2026_06_15_000300_add_reference_and_verses_refs_to_verses_table.php`), por lo que se ordena al final.

### Columnas (tipos, nulabilidad, defaults, casts)

| Columna | Tipo migración | Nulabilidad | Default | Cast en modelo |
|---|---|---|---|---|
| `id` | `id()` (bigint PK) | — | auto | — |
| `platform` | `string('platform', 20)` | not null | `'youtube'` | — |
| `youtube_channel_id` | `string(64)` | nullable | `null` | — |
| `youtube_url` | `string(500)` | nullable | `null` | — |
| `facebook_url` | `string(500)` | nullable | `null` | — |
| `tiktok_url` | `string(500)` | nullable | `null` | — |
| `x_url` | `string(500)` | nullable | `null` | — |
| `instagram_url` | `string(500)` | nullable | `null` | — |
| `window_day` | `unsignedTinyInteger` | not null | `7` (domingo, ISO-8601 `dayOfWeekIso`) | `integer` |
| `window_start` | `time` | not null | `'09:00:00'` | — (ver nota de normalización) |
| `window_end` | `time` | not null | `'13:00:00'` | — (ver nota de normalización) |
| `mode` | `string('mode', 20)` | not null | `'auto'` | — |
| `archivar` | `boolean` | not null | `false` | `boolean` |
| `url_grabacion` | `string(500)` | nullable | `null` | — |
| `title` | `string(255)` | nullable | `null` | — |
| `active` | `boolean` | not null | `true` | `boolean` |
| `created_at` / `updated_at` | `timestamps()` | — | — | `datetime` (implícito) |

Notas:
- `platform` admite solo `youtube` | `facebook` (validado en el controlador, no a nivel de columna, para seguir el estilo del repo que no usa enums de BD).
- `window_day` usa la convención **ISO-8601** (1 = lunes … 7 = domingo), idéntica a la usada en `AdminController` (`now()->dayOfWeekIso`) y en `ScheduleController` (`Schedule::where('day', now()->dayOfWeekIso)`). Domingo = `7`.
- `window_start`/`window_end` se guardan como `time`. **Nota de normalización (resuelve hallazgo 5 de la revisión):** la columna `time` sin cast la devuelve Eloquent como cadena `HH:MM:SS` (p.ej. `'09:00:00'`), **no** `HH:MM`. Comparar lexicográficamente `'09:00'` contra `'09:00:00'` daría falso en el borde. Por eso el servicio **normaliza ambos extremos a `HH:MM`** con `substr($valor, 0, 5)` antes de comparar contra `now(config('app.timezone'))->format('H:i')`. Convención fijada: comparación de cadenas `HH:MM` (longitud idéntica), intervalo **semiabierto** `[window_start, window_end)`. Ver §2.3a para el fragmento exacto.
- `mode` admite `auto` | `forzar_vivo` | `forzar_oculto`.
- **Sin soft deletes**: el singleton no se borra nunca (a diferencia de `Worship`, que sí los usa). Se mantiene `active` por coherencia con el patrón `Banner` (`carousel_images` tiene `active`), aunque para un singleton su rol es informativo/futuro; no se usa como gate de visibilidad (eso lo decide `mode`).

### Casts del modelo

```php
protected $fillable = [
    'platform', 'youtube_channel_id', 'youtube_url',
    'facebook_url', 'tiktok_url', 'x_url', 'instagram_url',
    'window_day', 'window_start', 'window_end',
    'mode', 'archivar', 'url_grabacion', 'title', 'active',
];

protected $casts = [
    'window_day' => 'integer',
    'archivar'   => 'boolean',
    'active'     => 'boolean',
];
```

### `::current()` (firstOrCreate) y creación de la fila por defecto

El acceso de conveniencia vive en el modelo:

```php
public static function current(): self
{
    return static::firstOrCreate([], [
        'platform'     => 'youtube',
        'window_day'   => 7,        // domingo (ISO-8601)
        'window_start' => '09:00:00',
        'window_end'   => '13:00:00',
        'mode'         => 'auto',
        'archivar'     => false,
        'active'       => true,
    ]);
}
```

**Doble garantía de la fila por defecto** (decisión): se usa **`firstOrCreate` en el modelo** como fuente de verdad en tiempo de ejecución (idempotente, nunca lanza si la tabla está vacía) **y además** un seeder `database/seeders/LiveStreamSeeder.php` que llama a `LiveStream::current()` para dejar la fila sembrada tras `migrate --seed`. Se elige esta combinación porque el criterio de aceptación 1 exige que "se crea el singleton por defecto (vía seeder y/o `firstOrCreate`)" y porque el servicio/panel deben funcionar aunque el seeder no se haya ejecutado en el servidor compartido. El seeder se registra en `DatabaseSeeder::run()` con `$this->call(LiveStreamSeeder::class);` (se añade la línea sin tocar las existentes).

---

## 2. Lógica "¿en vivo ahora?" — `App\Services\LiveStreamService`

### Interfaz pública

```php
namespace App\Services;

class LiveStreamService
{
    public function isLive(): bool;          // atajo: getLiveState()['is_live']
    public function getLiveState(): array;   // estado completo normalizado
}
```

`isLive()` delega en `getLiveState()` y devuelve su `is_live`. Toda la lógica de decisión vive en `getLiveState()` para una única fuente de verdad.

### Orden de evaluación (precedencia)

1. **`mode === 'forzar_oculto'`** → `is_live = false`. Fin. No se mira ventana ni API.
2. **`mode === 'forzar_vivo'`** → `is_live = true`, `source = 'manual'`. Fin. No se llama a la API (red de seguridad para Facebook/TikTok/X/Instagram).
3. **`mode === 'auto'`**:
   a. Evalúa la **ventana horaria** en `config('app.timezone')` (`America/Bogota`). Convención de comparación fijada (resuelve hallazgo 5):

   ```php
   $tz      = config('app.timezone');            // 'America/Bogota'
   $now     = now($tz);
   $nowHm   = $now->format('H:i');               // 'HH:MM'
   $startHm = substr($live->window_start, 0, 5); // normaliza 'HH:MM:SS' -> 'HH:MM'
   $endHm   = substr($live->window_end, 0, 5);
   $enVentana = $now->dayOfWeekIso === (int) $live->window_day
             && $nowHm >= $startHm
             && $nowHm <  $endHm;                 // intervalo semiabierto [inicio, fin)
   ```

   Si está **fuera** (`!$enVentana`) → `is_live = false`, y **no se llama a la API** (ahorro de cuota). Fin. Ambos extremos se normalizan a `HH:MM` (misma longitud) para que la comparación de cadenas y el borde `09:00` exacto sean deterministas.
   b. Si está **dentro** de la ventana y **YouTube está configurado**, consulta la API de YouTube (ver §3). **Regla única de "YouTube configurado" (resuelve hallazgo 4):** el `channel_id` efectivo es el del singleton con *fallback* a config, y la key siempre viene de config:

   ```php
   $channelId = $live->youtube_channel_id ?: config('services.youtube.channel_id');
   $apiKey    = config('services.youtube.api_key');
   $ytConfigurado = filled($channelId) && filled($apiKey);
   ```

   Esta es la **única** definición de "YT cfg" en todo el diseño; la tabla de estados usa este `$channelId` efectivo (singleton → config), no solo el del singleton. Si `$ytConfigurado` es verdadero:
      - API responde **en vivo** → `is_live = true`, `source = 'youtube_api'`, embed con el `videoId` detectado.
      - API responde **no en vivo** → `is_live = true`, `source = 'window'` (fallback: estamos dentro de ventana; la detección de YouTube solo refina el embed, no puede apagar la señal de ventana, pensada para las redes sin API).
      - API **falla** (excepción/timeout/HTTP no exitoso) → `is_live = true`, `source = 'window'` (fallback).
   c. Si está **dentro** de la ventana y **YouTube NO está configurado** (`!$ytConfigurado`: sin `channelId` efectivo o sin key) → `is_live = true`, `source = 'window'`.

> **Regla de fallback exacta (documentar en el código):** dentro de ventana, cualquier situación en la que la API de YouTube no confirme un directo (no configurada, sin key, caída, o responde "no en vivo") resulta en **`is_live = true` con `source = 'window'`**. La ventana manda; YouTube solo mejora el embed cuando confirma.

### Tabla de estados completa

Leyenda: `YT cfg` = `$ytConfigurado` = `filled($channelId efectivo) && filled(api_key)`, donde `$channelId efectivo = $live->youtube_channel_id ?: config('services.youtube.channel_id')` (regla única de §2.3b). `API` = resultado de la llamada a YouTube Data API v3. `—` = no aplica / no se evalúa.

| # | `mode` | ¿En ventana? | YT cfg | API | `is_live` | `source` | ¿Llama API? |
|---|---|---|---|---|---|---|---|
| 1 | `forzar_oculto` | — | — | — | **false** | `manual` | No |
| 2 | `forzar_vivo` | — | — | — | **true** | `manual` | No |
| 3 | `auto` | No | — | — | **false** | `window` | No |
| 4 | `auto` | Sí | No (sin `channelId` efectivo) | — | **true** | `window` | No |
| 5 | `auto` | Sí | No (sin key) | — | **true** | `window` | No |
| 6 | `auto` | Sí | Sí | en vivo | **true** | `youtube_api` | Sí |
| 7 | `auto` | Sí | Sí | no en vivo | **true** | `window` | Sí |
| 8 | `auto` | Sí | Sí | falla/timeout | **true** | `window` | Sí |

Notas de ambigüedad resueltas:
- En los casos 1 y 2, `source = 'manual'` aunque no haya ventana ni API, porque la decisión es un override humano.
- En el caso 3 (fuera de ventana), `source = 'window'` acompaña a `is_live = false` por coherencia del campo (indica que la decisión la tomó la ventana); el front solo mira `is_live`.
- La API jamás puede producir `is_live = false` dentro de ventana en modo `auto`: solo decide si `source` es `youtube_api` (confirma y aporta `videoId`) o `window` (fallback).

### Forma exacta de `getLiveState()`

```php
[
    'is_live'    => bool,
    'platform'   => 'youtube'|'facebook',          // plataforma principal incrustable
    'embed_url'  => string|null,                    // null si is_live=false o sin embed posible
    'watch_urls' => [
        'youtube'   => string|null,
        'facebook'  => string|null,
        'tiktok'    => string|null,
        'x'         => string|null,
        'instagram' => string|null,
    ],
    'title'      => string|null,                    // opcional (título del directo o LiveStream::title)
    'source'     => 'youtube_api'|'window'|'manual',
]
```

Construcción de `embed_url` según `platform` cuando `is_live = true`:
- `youtube` + `source = 'youtube_api'` → `https://www.youtube.com/embed/{videoId}?autoplay=1`.
- `youtube` + `source` distinto (window/manual) → se intenta derivar el `videoId` de `youtube_url`. **Mecanismo de reutilización fijado (resuelve hallazgo 3):** el regex de `Banner::detectYoutubeId()` es un método `protected` de instancia (verificado en `app/Models/Banner.php`), **no invocable** desde el servicio. Se extrae a un helper estático reutilizable y se usa desde ambos sitios:

  - **Clase nueva:** `App\Support\YouTube` (archivo `app/Support/YouTube.php`).
  - **Método público estático:** `YouTube::extractId(?string $url): ?string`, con el patrón canónico ya presente en el repo:

    ```php
    public static function extractId(?string $url): ?string
    {
        if (empty($url)) { return null; }
        $ok = preg_match(
            '/(?:youtube(?:-nocookie)?\.com\/(?:.*[?&]v=|embed\/|shorts\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/',
            $url, $m
        );
        return $ok ? $m[1] : null;
    }
    ```

  - **Refactor mínimo de `Banner`:** `Banner::detectYoutubeId()` pasa a delegar en `YouTube::extractId()` (`return YouTube::extractId($value);`), para que exista una sola fuente del regex y no haya duplicación. Es un cambio interno que no altera el comportamiento público de `Banner` (los accessors `*MediaEmbedUrl` siguen funcionando igual).
  - El servicio usa `YouTube::extractId($live->youtube_url)`; si no hay id extraíble, `embed_url = null` y el front muestra el bloque de "ver en" sin iframe.
- `facebook` → embed del plugin de video de Facebook a partir de `facebook_url`: `https://www.facebook.com/plugins/video.php?href={urlencode(facebook_url)}&autoplay=1`; si `facebook_url` está vacío, `embed_url = null`. **Advertencia (hallazgo 8):** el plugin de video de Facebook puede no reproducir un directo sin configuración de dominio/SDK; se trata como un iframe simple, **sin dependencia de SDK**, y el front degrada al bloque de "ver también en…" como enlace externo si el iframe no carga (ver §4). Facebook es secundario; la vía fiable es el botón de override + enlace externo.

`watch_urls` copia directamente las columnas (`youtube_url`, `facebook_url`, `tiktok_url`, `x_url`, `instagram_url`), dejando `null` las vacías para que el front no pinte botones vacíos (RF-1.2).

### Testabilidad

`LiveStreamService` es **unit-testeable**: se inyecta/instancia el modelo singleton y se controla el reloj con `Carbon::setTestNow()` para forzar dentro/fuera de ventana; la llamada a YouTube se aísla en un método `protected detectYouTubeLive(): ?array` que se puede mockear o cubrir con `Http::fake()`. Los 8 estados de la tabla se cubren con un test por fila. El fallback (caso 8) se prueba con `Http::fake()` devolviendo 500 o lanzando excepción.

---

## 3. Detección por YouTube Data API v3 + endpoint proxy

### Endpoint de YouTube elegido y coste de cuota

Se usa **`GET https://www.googleapis.com/youtube/v3/search`** con:

```
part=snippet
channelId={youtube_channel_id}
eventType=live
type=video
key={config('services.youtube.api_key')}
maxResults=1
```

**Decisión search vs videos:** se elige `search` (no `videos`) porque `videos.list` requiere conocer de antemano el `videoId`, que es justo lo que no tenemos: el `videoId` del directo cambia en cada transmisión. `search` con `eventType=live` es el único endpoint que localiza "el directo activo de este canal" sin conocer el id previo. **Coste documentado: `search.list` cuesta ~100 unidades de cuota por llamada** (la cuota diaria por defecto es 10.000 unidades). Por eso la llamada solo ocurre **dentro de la ventana** y su resultado se **cachea** (ver abajo); en el peor caso domingo 09:00–13:00 refrescando cada 3 min son ~80 llamadas ≈ 8.000 unidades, dentro de cuota, y en la práctica muchas menos por el caché compartido.

### Extracción del `videoId` y construcción del embed

De la respuesta JSON se toma `items[0].id.videoId` (y opcionalmente `items[0].snippet.title` para `title`). El embed es:

```
https://www.youtube.com/embed/{videoId}?autoplay=1
```

Si `items` viene vacío → se interpreta como "no en vivo" (caso 7 de la tabla).

### Estrategia de caché de la detección

- **Clave:** `livestream:youtube_detect` (cadena fija; el singleton es único).
- **TTL:** **3 minutos** (`now()->addMinutes(3)`), dentro del rango 2–5 min exigido.
- Se cachea el **resultado normalizado** de la detección (`['live' => bool, 'video_id' => ?string, 'title' => ?string]`), no la respuesta cruda, para que el fallback por excepción no contamine el caché con basura: ante excepción/HTTP fallido se devuelve `['live' => false, ...]` **sin cachear el error** largo tiempo (se cachea 30 s el fallo para no martillar la API en una ventana con la API caída; decisión conservadora análoga a los 10 s de WideStream).

### Degradación sin API key / sin canal

Si `config('services.youtube.api_key')` está vacía o falta `youtube_channel_id`, el servicio **no llama a la API** y resuelve por ventana (casos 4 y 5). Nunca lanza excepción por falta de configuración. Esto cumple el criterio de aceptación 5.

### Configuración (config/services.php + env)

Se añade el bloque `youtube` a `config/services.php`, replicando el patrón `widestream`:

```php
'youtube' => [
    'api_key'    => env('YOUTUBE_API_KEY'),
    'channel_id' => env('YOUTUBE_CHANNEL_ID'),
],
```

- La key se lee **siempre** vía `config('services.youtube.api_key')`, nunca `env()` directo fuera de config (RNF-1).
- **Fuente de verdad del `channel_id` (regla única, coherente con §2.3b y la tabla de estados):** el servicio usa

  ```php
  $channelId = $live->youtube_channel_id ?: config('services.youtube.channel_id');
  ```

  es decir, el valor del singleton manda y, **solo si está vacío**, cae al de config (`YOUTUBE_CHANNEL_ID`). No hay ninguna otra regla en el diseño: tanto §2.3b como la columna "YT cfg" de la tabla de estados se evalúan sobre este mismo `$channelId` efectivo. Consecuencia explícita (antes contradictoria, ahora fijada): con el singleton vacío pero `YOUTUBE_CHANNEL_ID` presente en env, **sí** se considera YouTube configurado y, dentro de ventana, **sí** se llama a la API. Así el operador puede configurarlo por panel sin tocar `.env`, pero existe un default por entorno.

### Contrato exacto del endpoint proxy `GET /api/live-status`

Calcado del patrón `AdminController@nowPlaying` (proxy server-side + `Cache::remember` + `response()->json(..., 200)`).

- **Método / ruta:** `GET /api/live-status`
- **Nombre de ruta:** `api.live.status`
- **Ubicación en `routes/web.php`:** en la zona **pública** (fuera del grupo `['auth','admin']`), junto a las demás rutas `/api/*` públicas (p.ej. cerca de `/api/programa-actual`). Es público (sin auth) porque lo consumen el banner de la home y la página `/en-vivo` para visitantes anónimos.
- **Controlador/acción:** `App\Http\Controllers\LiveController@status` (mismo controlador que sirve la página pública, ver §4/§5).
- **Caché server-side:** `Cache::remember('livestream:status', now()->addMinutes(3), fn() => app(LiveStreamService::class)->getLiveState())`. TTL 3 min, coherente con el caché de detección. (La key del proxy y la de detección son distintas pero con el mismo TTL; el proxy cachea el estado completo ya normalizado.)
- **Forma del JSON de respuesta (siempre HTTP 200):**

```json
{
  "is_live": true,
  "platform": "youtube",
  "embed_url": "https://www.youtube.com/embed/XXXXXXXXXXX?autoplay=1",
  "watch_urls": {
    "youtube": "https://youtube.com/@canal/live",
    "facebook": "https://facebook.com/pagina/live",
    "tiktok": null,
    "x": null,
    "instagram": null
  },
  "title": "Culto Dominical",
  "source": "youtube_api"
}
```

Es exactamente el array de `getLiveState()` serializado a JSON. El endpoint nunca devuelve 500 por lógica de negocio: cualquier fallo de YouTube ya quedó absorbido por el fallback del servicio; solo un error inesperado de framework daría 5xx. **La API key nunca aparece en la respuesta** (RNF-1).

### Manejo de errores concreto (§3)

| Operación | Condición de fallo | ¿Recuperable? | Qué recibe el llamador | ¿Log? |
|---|---|---|---|---|
| Llamada HTTP a YouTube | Timeout (5 s), HTTP ≠ 2xx, excepción de red | Recuperable (fallback) | `getLiveState()` con `source='window'`, `is_live=true` (si en ventana) | `Log::warning('YouTube live detect falló', ['e' => $e->getMessage()])` a nivel warning (no error): es esperado y no rompe UX |
| Parseo de respuesta YouTube | JSON sin `items` o estructura inesperada | Recuperable | Tratado como "no en vivo" → fallback window | Sin log (caso normal "no hay directo") |
| Falta de API key / channel_id | Config vacía | No es error (degradación) | Resolución por ventana | Sin log |
| `/api/live-status` | Error de framework inesperado | Fatal para esa request | HTTP 500 del handler global | Log del handler estándar de Laravel |

Timeout de la llamada HTTP: **5 segundos** (`->timeout(5)`), igual que WideStream.

---

## 4. Banner en la home + página pública `/en-vivo`

### Partial del banner — `resources/views/partials/live-banner.blade.php`

- Marcado oculto por defecto (`x-data="{ live:false, state:null }"`, contenedor `x-show="live"`), que hace `fetch('/api/live-status')` en `x-init` (o al cargar vía `document.addEventListener('DOMContentLoaded')`), y solo se muestra si `state.is_live === true`.
- Diseño llamativo Tailwind de marca: franja con fondo `emerald`, texto **"EN VIVO AHORA — Culto Dominical"**, un punto animado de pulso reutilizando el estilo del topbar/panel (clases `bg-emerald-500` + `animate-pulse` / anillo; se replica el "Indicador Radio En Vivo" visto en `layouts/panel.blade.php` línea ~298) y un botón **"Ver ahora"** que enlaza a `route('live.index')` (`/en-vivo`).
- Se refresca periódicamente (p.ej. `setInterval` cada 60 s) para que un visitante que ya está en la home vea aparecer el banner al comenzar el culto (RF-4.3).

**Punto de inclusión:** al **inicio** del `@section('content')` de `resources/views/welcome.blade.php`, antes de la sección "Palabra del día":

```blade
@section('content')
  @include('partials.live-banner')
  <section aria-labelledby="home-quote" class="mb-8">
  ...
```

### Ruta y controlador de la página pública

- **Ruta:** `Route::get('/en-vivo', [LiveController::class, 'index'])->name('live.index');` en la zona pública de `routes/web.php` (cerca de `/worship`). No colisiona con la vieja `/live` (`HomeContentController@liveverse`), que **no se toca** (RF-5.1, RNF-2).
- **Controlador:** `App\Http\Controllers\LiveController`
  - `index()` → `return view('live.index', ['state' => app(LiveStreamService::class)->getLiveState()]);` (render server-side del primer estado; el front además refresca vía `fetch` para transiciones en vivo).
  - `status()` → endpoint proxy JSON (§3).
- **Vista pública:** `resources/views/live/index.blade.php`, `@extends('layouts.main')`, Tailwind de marca.

### Estructura del embed por plataforma (iframe de altura fija)

Coherente con `player.blade.php` / `radio-widget.blade.php` (iframe con `border-radius:16px`, `allow`, altura fija). Para video se usa contenedor responsivo 16:9 con altura fija de respaldo:

```blade
{{-- YouTube o Facebook según state.platform / state.embed_url --}}
<div class="relative w-full rounded-2xl overflow-hidden" style="aspect-ratio:16/9; min-height:360px;">
  <iframe
    src="{{ $state['embed_url'] }}"
    class="absolute inset-0 w-full h-full"
    frameborder="0"
    allow="autoplay; encrypted-media; picture-in-picture"
    allowfullscreen
    title="Culto en Vivo"
    style="border:0;"></iframe>
</div>
```

### Estado sin directo y botones a redes

- `is_live = false` → bloque "No hay transmisión en vivo en este momento", con enlace opcional a la última grabación (`url_grabacion` si existe) y/o a `/worship` (RF-5.4).
- `is_live = true` → debajo del embed, botones "Ver también en Facebook/TikTok/X/Instagram" **solo** para las `watch_urls` no nulas, como enlaces externos `target="_blank" rel="noopener"` (RF-5.3). TikTok/X/Instagram siempre como enlace (no incrustables).
- **Degradación del embed de Facebook (hallazgo 8):** cuando `platform = 'facebook'`, el iframe del plugin se renderiza, pero **siempre** se muestra también el botón externo "Ver también en Facebook" por si el embed no reproduce el directo (requisito de dominio/SDK que no se cubre aquí). No se introduce el SDK de Facebook. La `GUIA_TRANSMISION.md` anota que, en Facebook, lo garantizado es el enlace externo + el botón de override manual.

### Enlace en el menú público

Archivo real: **`resources/views/layouts/menu.blade.php`** (confirmado: es el menú incluido por `layouts.main`; `nav.blade.php` existe pero no lo usa la home).

- **Escritorio:** añadir en el bloque `.flex items-center space-x-1`, tras "Culto dominical":
  ```blade
  <a href="{{ url('en-vivo') }}" class="nav-link {{ request()->is('en-vivo') ? 'nav-link-active' : '' }}">En Vivo</a>
  ```
- **Móvil:** añadir el equivalente en el bloque `.space-y-1`:
  ```blade
  <a href="{{ url('en-vivo') }}" class="mobile-nav-link {{ request()->is('en-vivo') ? 'mobile-nav-link-active' : '' }}">En Vivo</a>
  ```
  Se usan las clases existentes `nav-link`/`nav-link-active` y `mobile-nav-link`/`mobile-nav-link-active` tal como los demás enlaces.

### Testabilidad (§4)

- `GET /en-vivo` → test de feature: 200, y con el servicio mockeado para `is_live=true` el HTML contiene el iframe; con `is_live=false` contiene "No hay transmisión en vivo en este momento".
- `GET /` → test de feature: 200 y el HTML incluye el partial (`id`/texto del banner presente en el DOM aunque oculto por `x-show`).
- `GET /api/live-status` → test de feature: 200 + JSON con las claves del contrato.

---

## 5. Panel de administración

### Controlador y acciones

- **Controlador:** `App\Http\Controllers\Admin\LiveSettingController` (nuevo). Alternativa considerada: reutilizar `LiveController` para admin y público; se **descarta** para separar responsabilidades (público vs. admin con middleware distinto), coherente con que el repo tiene controladores por sección.
- **Acciones:**
  - `show()` → vista de ajustes del singleton: `return view('admin.live.index', ['live' => LiveStream::current()]);`
  - `update(Request $request)` → valida y guarda sobre el singleton (`LiveStream::current()`), redirige con `->back()->with('success', ...)`.
  - `toggleLive()` → botón de override de un clic (ver abajo).
  - `archive()` → "Archivar como culto" (ver §6).

### Rutas (bajo `['auth','admin']`, convención del repo)

Dentro del grupo `Route::group(['middleware' => ['auth', 'admin']], ...)`, siguiendo el estilo `/show-X`, `/update-X`:

```php
Route::get('/show-live', [LiveSettingController::class, 'show'])->name('live.admin.show');
Route::post('/update-live', [LiveSettingController::class, 'update'])->name('live.admin.update');
Route::post('/toggle-live', [LiveSettingController::class, 'toggleLive'])->name('live.admin.toggle');
Route::post('/archive-live', [LiveSettingController::class, 'archive'])->name('live.admin.archive');
```

(Como es singleton, `update`/`toggle`/`archive` no llevan `{id}`, a diferencia del CRUD de lista del repo; es la adaptación correcta del patrón para un singleton, prevista en los requisitos.)

### Vista(s)

- `resources/views/admin/live/index.blade.php`, `@extends('layouts.panel')`.
- Un único formulario `POST /update-live` con `@csrf` que edita: `platform` (select youtube/facebook), URLs por plataforma (5 inputs), `youtube_channel_id`, ventana (`window_day` select de días con domingo por defecto, `window_start`/`window_end` inputs `time`), `mode` (select con 3 opciones etiquetadas en español), `archivar` (checkbox) + `url_grabacion` + `title`.
- Botón de override destacado (ver abajo) y botón "Archivar como culto" (§6).

### Validación (español, por campo)

En `update()`:

```php
$request->validate([
    'platform'           => ['required', 'in:youtube,facebook'],
    'youtube_channel_id' => ['nullable', 'string', 'max:64'],
    'youtube_url'        => ['nullable', 'url', 'max:500'],
    'facebook_url'       => ['nullable', 'url', 'max:500'],
    'tiktok_url'         => ['nullable', 'url', 'max:500'],
    'x_url'              => ['nullable', 'url', 'max:500'],
    'instagram_url'      => ['nullable', 'url', 'max:500'],
    'window_day'         => ['required', 'integer', 'between:1,7'],
    'window_start'       => ['required', 'date_format:H:i'],
    'window_end'         => ['required', 'date_format:H:i', 'after:window_start'],
    'mode'               => ['required', 'in:auto,forzar_vivo,forzar_oculto'],
    'archivar'           => ['nullable', 'boolean'],
    'url_grabacion'      => ['nullable', 'url', 'max:500'],
    'title'              => ['nullable', 'string', 'max:255'],
], [
    'platform.required'      => 'La plataforma principal es obligatoria.',
    'platform.in'            => 'La plataforma debe ser YouTube o Facebook.',
    'youtube_url.url'        => 'La URL de YouTube no es válida.',
    'facebook_url.url'       => 'La URL de Facebook no es válida.',
    'tiktok_url.url'         => 'La URL de TikTok no es válida.',
    'x_url.url'              => 'La URL de X no es válida.',
    'instagram_url.url'      => 'La URL de Instagram no es válida.',
    'window_day.required'    => 'El día de la ventana es obligatorio.',
    'window_day.between'     => 'El día debe estar entre lunes (1) y domingo (7).',
    'window_start.required'  => 'La hora de inicio es obligatoria.',
    'window_start.date_format' => 'La hora de inicio debe tener formato HH:MM.',
    'window_end.required'    => 'La hora de fin es obligatoria.',
    'window_end.date_format' => 'La hora de fin debe tener formato HH:MM.',
    'window_end.after'       => 'La hora de fin debe ser posterior a la de inicio.',
    'mode.required'          => 'El modo de visibilidad es obligatorio.',
    'mode.in'                => 'El modo debe ser Automático, Forzar en vivo o Forzar oculto.',
    'url_grabacion.url'      => 'La URL de la grabación no es válida.',
]);
```

El checkbox `archivar` se normaliza a booleano con `$request->boolean('archivar')` antes de guardar. `window_start`/`window_end` llegan como `H:i` y se guardan como `H:i:00`.

### Botón de override de un clic

- Vive **en la vista de ajustes `admin/live/index` y también como tarjeta en el dashboard** (`admin/dashboard`), para acceso rápido el domingo (RF-7.4 pide "disponible en el panel/dashboard"). Es un `form POST` a `route('live.admin.toggle')` con `@csrf`.
- Comportamiento de `toggleLive()`: alterna en un clic según el estado actual:
  - Si `mode !== 'forzar_vivo'` → pasa a `forzar_vivo` (botón rojo "Estamos EN VIVO ahora").
  - Si `mode === 'forzar_vivo'` → vuelve a `auto` (botón "Finalizar transmisión"), **no** a `forzar_oculto`, para que fuera de ventana el banner no reaparezca pero dentro de ventana la lógica normal retome el control. `forzar_oculto` queda disponible explícitamente en el select del formulario para quien quiera forzar el apagado total.
- Tras togglear: **invalida el caché** (`Cache::forget('livestream:status'); Cache::forget('livestream:youtube_detect');`) para que el banner refleje el cambio de inmediato, y redirige `->back()->with('success', ...)`.

### Enlace en el menú del panel

Archivo real: **`resources/views/layouts/panel.blade.php`**, dentro del nav-group existente **"Radio & Emisión"** (`<div class="nav-section-title">Radio & Emisión</div>`, ~línea 123), tras el enlace de "Cultos Dominicales":

```blade
<a href="{{ url('show-live') }}" class="nav-item group {{ request()->is('show-live*') ? 'active' : '' }}" title="Culto en Vivo (Transmisión Dominical)">
    {{-- icono emisión --}}
    <span class="nav-text">Culto en Vivo</span>
</a>
```

### Testabilidad (§5)

- `GET /show-live` sin auth → 302 a login (nunca 500) — criterio 9.
- Autenticado admin → 200; `POST /update-live` con datos válidos persiste en el singleton (assert sobre `LiveStream::current()`), con datos inválidos → 302 con errores de validación.
- `POST /toggle-live` alterna `mode` entre `forzar_vivo` y `auto`.

---

## 6. Archivado de la grabación

**Decisión:** implementar **el botón explícito "Archivar como culto"** (RF-8.2), que es lo preferido por los requisitos, **además** de persistir siempre la `url_grabacion` en el singleton al guardar el formulario (RF-8.1). Justificación: guardar solo la URL no cumple el criterio de aceptación 10 ("el botón crea un registro `Worship` enlazado"); y crear el `Worship` automáticamente al guardar sería frágil (podría duplicar cultos). Un botón explícito da control al operador y es idempotente por fecha.

`archive()` (acción de `POST /archive-live`):

1. Toma `LiveStream::current()`; requiere `url_grabacion` no vacía (si falta → `->back()->withErrors(['url_grabacion' => 'Debes indicar la URL de la grabación antes de archivar.'])`).
2. Calcula la **fecha del domingo** objetivo: el domingo de la semana actual según `config('app.timezone')` (`Carbon::now(config('app.timezone'))->startOfWeek(Carbon::SUNDAY)` o el domingo más reciente). Decisión: usar el **último domingo ≤ hoy** para que archivar un lunes apunte al culto del domingo pasado.
3. Crea/actualiza un `Worship` mapeando a columnas **reales** de la tabla `worships`. **Restricciones reales verificadas en la migración `2023_10_31_154704_create_worships_table.php`** (no solo en `$fillable`, que no revela estas restricciones):

   - El índice **`UNIQUE` vive en la columna `title`** (`$table->string('title')->unique()`), **no en `slug`** (`$table->string('slug')` sin índice único). → *resuelve hallazgo 2.*
   - Son **NOT NULL y SIN default**: `abstract` (`text`), `badge` (`text`), `pdfdoc` (`string`). Omitirlas haría fallar el `INSERT` con error de integridad (SQL 1364). → *resuelve hallazgo 1.*
   - `image` tiene default `'generic.png'`; `audio`, `video`, `urlyt`, `deleted_at` son nullable; `autor` es `string(30)`.

   **Desambiguación del `title` (no del slug)** — el bucle que `WorshipController::store` aplica al slug se aplica aquí al `title`, porque es la columna con `UNIQUE`, excluyendo la propia fila del domingo que se re-archiva:

   ```php
   // IMPORTANTE (corrige Hallazgo 1 / soft deletes): el índice UNIQUE de `title`
   // sigue activo en BD aunque la fila esté soft-deleted. Por eso TODA detección de
   // colisión y el updateOrCreate usan withTrashed(), o un INSERT chocaría con una
   // fila borrada del mismo título/fecha y lanzaría un 500.

   // 1) Si ya existe un culto (incluso borrado) para ese domingo, se restaura y se
   //    actualiza en vez de insertar uno nuevo.
   $existing = Worship::withTrashed()->where('broadcast', $fecha)->first();
   if ($existing && $existing->trashed()) {
       $existing->restore();
   }

   // 2) Desambiguación del title contando TAMBIÉN los soft-deleted (withTrashed).
   $baseTitle = $live->title ?: ('Culto Dominical — ' . $fecha->format('d/m/Y'));
   $title = $baseTitle; $n = 1;
   while (Worship::withTrashed()->where('title', $title)
           ->where('broadcast', '!=', $fecha)   // no colisiona consigo mismo al re-archivar
           ->exists()) {
       $title = $baseTitle . ' (' . (++$n) . ')';
   }
   $slug = Str::slug($title);                    // slug derivado; la BD no exige unicidad en slug
   ```

   Mapeo final a columnas reales (todas las NOT NULL presentes, con los mismos valores que usa `WorshipController::store`):

| Campo Worship | Valor | Motivo |
|---|---|---|
| `broadcast` | fecha del domingo objetivo (cast `date`) | clave de idempotencia del `updateOrCreate` |
| `title` | `$title` desambiguado (ver bucle) | **columna UNIQUE**; debe no colisionar |
| `slug` | `Str::slug($title)` | derivado; sin UNIQUE en BD |
| `abstract` | `''` | **NOT NULL sin default**; `store` usa `''` |
| `badge` | `'Culto Dominical'` | **NOT NULL sin default**; `store` usa `'Culto Dominical'` |
| `pdfdoc` | `''` | **NOT NULL sin default**; `store` usa `''` |
| `autor` | `'Culto en Vivo'` (≤ 30 chars) | `string(30)` |
| `video` | `$live->url_grabacion` | URL del video |
| `urlyt` | `$live->url_grabacion` | el repo usa `urlyt` para enlaces de YouTube; compatibilidad con la vista de cultos |

   `image` se omite (usa su default `'generic.png'`); `audio` queda nullable.

   Llamada idempotente:

   ```php
   // updateOrCreate con withTrashed(): si existe una fila (incluida soft-deleted) para
   // ese domingo, la actualiza (ya restaurada en el paso 1); si no, inserta. Así nunca
   // se intenta un INSERT que choque con el UNIQUE de `title`/`broadcast` de una fila borrada.
   Worship::withTrashed()->updateOrCreate(
       ['broadcast' => $fecha],   // un culto por domingo (a nivel de aplicación)
       [
           'title' => $title, 'slug' => $slug,
           'abstract' => '', 'badge' => 'Culto Dominical', 'pdfdoc' => '',
           'autor' => 'Culto en Vivo',
           'video' => $live->url_grabacion, 'urlyt' => $live->url_grabacion,
       ]
   );
   ```

   **Idempotencia (nota del hallazgo 7) y soft deletes (Hallazgo 1):** `broadcast` **no** tiene índice único en BD; la no duplicación por domingo la garantiza `updateOrCreate` **a nivel de aplicación**, usando `withTrashed()` para considerar también cultos soft-deleted (y restaurarlos en vez de insertar un duplicado que violaría el UNIQUE de `title`). No se añade índice único sobre `broadcast` para no arriesgar colisión con cultos históricos que ya pudieran compartir fecha. La unicidad por domingo es a nivel de aplicación.

   **Nota sobre `autor` (Hallazgo 3):** el valor `'Culto en Vivo'` es propio del archivado (identifica el origen del registro), NO una copia de `WorshipController::store` (que usa `'P. Henry Belalcázar'`). Es intencional para distinguir los cultos archivados desde el directo.
4. Redirige `->back()->with('success', 'Culto archivado correctamente.')`.

El archivado es **manual asistido**; no se automatiza captura por API (fuera de alcance, RF-8.3).

### Manejo de errores (§6)

| Condición | Recuperable | Respuesta | Log |
|---|---|---|---|
| `url_grabacion` vacía al archivar | Sí | `->back()->withErrors([...])` en español | No |
| Colisión de `title` (UNIQUE) | Recuperable (preventivo) | El bucle de desambiguación evita la colisión antes del `INSERT`; no llega excepción | No |
| Fallo de BD al `updateOrCreate` (NOT NULL / otro) | Fatal para la request | 500 del handler global | Log estándar de Laravel |

### Testabilidad (§6)

Feature test: con `url_grabacion` fijada, `POST /archive-live` crea un `Worship` con `broadcast` = domingo, `video`/`urlyt` = URL, título por defecto y las columnas NOT NULL (`abstract`, `badge`, `pdfdoc`) pobladas; segundo POST del mismo domingo no duplica (updateOrCreate por `broadcast`). Test de colisión: sembrar un `Worship` preexistente con el mismo `title` base y otra `broadcast` → el archivado genera un `title` con sufijo ` (2)` y no lanza excepción de unicidad.

---

## 7. Documento de operación — `GUIA_TRANSMISION.md`

- **Ubicación:** raíz del worktree → `GUIA_TRANSMISION.md` (junto a los demás `.md` del proyecto; accesible para el operador y versionable). En esta fase solo se define su índice; el contenido se redacta en implementación (RF-9).
- **Índice de secciones (cubre RF-9.1–RF-9.2):**
  1. **Introducción** — qué es, para quién (persona no técnica).
  2. **Requisitos previos** — PC, conexión estable, OBS Studio (gratis), canal de YouTube verificado.
  3. **Obtener la clave de transmisión (stream key RTMP) de YouTube Live** — pasos en YouTube Studio → Transmitir en directo, con enlaces oficiales.
  4. **Configurar OBS Studio** — descarga/instalación; pegar servidor RTMP + stream key; verificación de conexión.
  5. **Dos cámaras en OBS** — añadir fuentes de video, crear escenas, cambiar entre cámaras en directo, encuadres.
  6. **Audio** — fuente de audio (mesa/micrófono), monitoreo, niveles.
  7. **Escena básica** — logo, título "Culto Dominical", orden de capas.
  8. **Ajustes recomendados de OBS** — bitrate, resolución (720p/1080p), fps, codificador.
  9. **Flujo del domingo** — iniciar directo en YouTube, verificar que aparece el banner en la web, al terminar detener en OBS/YouTube y archivar desde el panel.
  10. **Obtener la YouTube Data API KEY (Google Cloud Console)** — crear proyecto, habilitar YouTube Data API v3, crear credencial API key; dónde ponerla (`YOUTUBE_API_KEY` en el `.env` del servidor) y el `YOUTUBE_CHANNEL_ID`; enlaces oficiales.
  11. **Transmitir por Facebook u otra red** — cómo usar el botón "Estamos EN VIVO ahora" (override manual) cuando no se usa YouTube; nota de que el embed de Facebook puede no reproducir el directo (requiere dominio/SDK) y que lo garantizado es el enlace externo "Ver también en Facebook".
  12. **Solución de problemas** — banner no aparece (revisar modo/ventana/key), video no carga, etc.

---

## 8. Lista de archivos y orden de implementación

### Archivos a crear

1. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/database/migrations/2026_06_16_000000_create_live_streams_table.php`
2. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/app/Models/LiveStream.php`
3. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/database/seeders/LiveStreamSeeder.php`
4. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/app/Support/YouTube.php` (helper `extractId()` reutilizable por `Banner` y el servicio)
5. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/app/Services/LiveStreamService.php`
6. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/app/Http/Controllers/LiveController.php` (público: `index`, `status`)
7. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/app/Http/Controllers/Admin/LiveSettingController.php` (admin: `show`, `update`, `toggleLive`, `archive`)
8. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/partials/live-banner.blade.php`
9. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/live/index.blade.php`
10. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/admin/live/index.blade.php`
11. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/GUIA_TRANSMISION.md`

### Archivos a modificar

12. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/app/Models/Banner.php` — `detectYoutubeId()` delega en `YouTube::extractId()` (fuente única del regex; hallazgo 3).
13. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/config/services.php` — añadir bloque `youtube`.
14. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/routes/web.php` — rutas admin (`/show-live`, `/update-live`, `/toggle-live`, `/archive-live`) y públicas (`/en-vivo`, `/api/live-status`).
15. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/welcome.blade.php` — `@include('partials.live-banner')` al inicio del content.
16. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/layouts/menu.blade.php` — enlace "En Vivo" (escritorio + móvil).
17. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/layouts/panel.blade.php` — enlace "Culto en Vivo" en nav-group "Radio & Emisión".
18. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/resources/views/admin/dashboard.blade.php` — tarjeta/botón de override rápido.
19. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/database/seeders/DatabaseSeeder.php` — registrar `LiveStreamSeeder`.
20. `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/.env.example` — documentar vars nuevas.

### Variables de entorno nuevas (en `.env.example`, bloque junto a WideStream; NO tocar el `.env` real)

```
YOUTUBE_API_KEY=
YOUTUBE_CHANNEL_ID=
```

### Orden de implementación

1. Migración + modelo `LiveStream` (`::current()`) + seeder + registro en `DatabaseSeeder` → `migrate --seed`.
2. Bloque `youtube` en `config/services.php` + vars en `.env.example`.
3. Helper `App\Support\YouTube::extractId()` + refactor de `Banner::detectYoutubeId()` para delegar en él.
4. `LiveStreamService` (lógica + tabla de estados + detección YouTube + caché) con sus unit tests.
5. `LiveController` (`index`, `status`) + rutas públicas `/en-vivo` y `/api/live-status`.
6. Partial del banner + inclusión en `welcome.blade.php` + vista `live/index` + enlace en `menu.blade.php`.
7. `Admin\LiveSettingController` + rutas admin + vista `admin/live/index` + validación en español.
8. Botón de override en `admin/live/index` y tarjeta en `admin/dashboard` + enlace en `panel.blade.php`.
9. Archivado (`archive()` → `Worship`) + test.
10. `GUIA_TRANSMISION.md`.
11. Verificación global (criterios de aceptación 1–12) y regresión (`/`, `/about`, `/biblia`, `/worship`, `/donate`, `/library`, `/en-vivo`).

---

## Decisiones clave (resumen)

- **Modelo/tabla:** `LiveStream` → `live_streams`, singleton vía `::current()` (`firstOrCreate`) + seeder; migración `2026_06_16_000000` (posterior a la última real, `2026_06_15_000300`). Defaults: domingo (ISO `7`), 09:00, 13:00, `mode='auto'`, `archivar=false`. Sin soft deletes.
- **Precedencia del servicio:** `forzar_oculto` → `forzar_vivo` → `auto` (ventana primero, luego YouTube como refinamiento, nunca como apagado). Fallback: dentro de ventana sin confirmación de YouTube ⇒ `is_live=true`, `source='window'`.
- **Detección YouTube:** `search.list` (`eventType=live`, ~100 u. de cuota), caché 3 min, solo dentro de ventana, degradación limpia sin key.
- **Endpoint proxy:** `GET /api/live-status` (name `api.live.status`), público, cacheado 3 min, JSON = `getLiveState()`, key nunca expuesta; calcado de `AdminController@nowPlaying`.
- **Archivado:** botón explícito "Archivar como culto" + persistencia de `url_grabacion`; `updateOrCreate` sobre `Worship` por `broadcast` (domingo). El **`title`** (columna UNIQUE real) se desambigua con bucle; `slug` es derivado. Se pueblan las columnas NOT NULL sin default (`abstract=''`, `badge='Culto Dominical'`, `pdfdoc=''`), además de `video`/`urlyt`/`autor`. Idempotencia por `broadcast` a nivel de aplicación.

**Ruta del documento de diseño:** `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo/.agents/tasks/diseno.md`

---

## Respuestas a la revisión de diseño (`design-review.md`, veredicto CHANGES_REQUESTED)

Todos los hallazgos se verificaron contra el código real del worktree antes de resolverlos: `database/migrations/2023_10_31_154704_create_worships_table.php` (restricciones de `worships`), `app/Models/Banner.php` (`detectYoutubeId` `protected`), `app/Http/Controllers/WorshipController.php` (valores por defecto de `store`) y `routes/web.php` (`nowPlaying` bajo `['auth','admin']`, `/api/programa-actual` público).

- **Hallazgo 1 [HIGH] — columnas NOT NULL del archivado → ABORDADO.** §6: el `updateOrCreate` de `Worship` ahora incluye `abstract=''`, `badge='Culto Dominical'`, `pdfdoc=''` (los tres NOT NULL sin default, verificados en la migración), con los mismos valores que `WorshipController::store`. Tabla de mapeo y bloque de código actualizados.
- **Hallazgo 2 [HIGH] — UNIQUE en `title`, no en `slug` → ABORDADO.** §6: se desambigua el **`title`** con el bucle `while (Worship::where('title',$title)->where('broadcast','!=',$fecha)->exists())`, y el `slug` se deriva de ese título (`Str::slug`). Se documenta explícitamente que el índice UNIQUE vive en `title`.
- **Hallazgo 3 [MEDIUM] — `Banner::detectYoutubeId()` es `protected` → ABORDADO (opción a).** Se extrae el regex a un helper público estático `App\Support\YouTube::extractId()` (nueva clase `app/Support/YouTube.php`), usado por `LiveStreamService`, y `Banner::detectYoutubeId()` se refactoriza para delegar en él (fuente única del patrón). Añadido a §2, a la lista de archivos (crear `app/Support/YouTube.php`; modificar `app/Models/Banner.php`) y al orden de implementación.
- **Hallazgo 4 [MEDIUM] — fuente de verdad de `youtube_channel_id` → ABORDADO.** Regla única fijada en §2.3b y §3: `$channelId = $live->youtube_channel_id ?: config('services.youtube.channel_id')`; `$ytConfigurado = filled($channelId) && filled($apiKey)`. La columna "YT cfg" de la tabla de estados se reescribió en términos de ese `channelId` efectivo. Se explicita la consecuencia (singleton vacío + env presente ⇒ sí llama a la API).
- **Hallazgo 5 [MEDIUM] — comparación de ventana `time` vs `H:i` → ABORDADO.** §1 (nota de normalización) y §2.3a: ambos extremos se normalizan con `substr($valor,0,5)` a `HH:MM` y se comparan contra `now($tz)->format('H:i')` en intervalo semiabierto `[inicio, fin)`. Fragmento de código fijado; borde `09:00` determinista.
- **Hallazgo 6 [NIT] — "calcado 1:1 de nowPlaying" → ABORDADO.** Reformulado en Visión general y §3: se replica el *mecanismo* server-side de `nowPlaying` (Http + Cache + json 200, key solo en servidor) pero **expuesto como ruta pública** (modelo de visibilidad de `/api/programa-actual`), no "1:1" en middleware.
- **Hallazgo 7 [NIT] — idempotencia de `broadcast` sin índice único → ABORDADO (documentado).** §6: se deja constancia de que la no duplicación por domingo es a nivel de aplicación (`updateOrCreate`), y se decide **no** añadir índice único sobre `broadcast` para no colisionar con cultos históricos ya duplicados por fecha.
- **Hallazgo 8 [NIT] — embed de Facebook requiere dominio/SDK → ABORDADO.** §2 y §4: se anota que el embed de Facebook puede no reproducir el directo; la UI siempre ofrece el enlace externo "Ver también en Facebook" como degradación, sin introducir el SDK. Reflejado también en el índice de `GUIA_TRANSMISION.md` (§7).

Ningún hallazgo se dejó en backlog ni se ignoró; las ocho resoluciones son coherentes con los requisitos (RF-8 archivado, RF-2/RF-3 lógica y detección, RNF-1 seguridad de la key, RNF-4 convenciones).
