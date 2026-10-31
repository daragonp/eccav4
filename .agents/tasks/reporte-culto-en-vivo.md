# Reporte final — Feature "Culto en Vivo"

Rama: `feat/culto-en-vivo` · Worktree: `/Users/daragonp/Proyectos/eccav4/.worktrees/culto-en-vivo`

Permite emitir el culto dominical en vivo en la web (YouTube/Facebook y enlaces a
otras redes), con un banner en la home y una página pública `/en-vivo`. La decisión
"¿estamos en vivo?" se resuelve server-side y se expone por el endpoint proxy
`GET /api/live-status`. La configuración se administra desde el panel.

---

## 1. Modelo de datos

Tabla singleton **`live_streams`** (una sola fila; acceso por
`App\Models\LiveStream::current()` con `firstOrCreate` idempotente).
Migración: `database/migrations/2026_06_16_000000_create_live_streams_table.php`.

| Columna | Tipo | Default | Descripción |
|---|---|---|---|
| `id` | bigint PK | auto | Identificador. |
| `platform` | string(20) | `youtube` | Plataforma principal del embed: `youtube` \| `facebook`. |
| `youtube_channel_id` | string(64) nullable | `null` | ID del canal (`UC...`); tiene prioridad sobre el del `.env`. |
| `youtube_url` | string(500) nullable | `null` | URL del directo/canal de YouTube para el embed y el botón "ver". |
| `facebook_url` | string(500) nullable | `null` | URL del directo de Facebook. |
| `tiktok_url` | string(500) nullable | `null` | Enlace a TikTok. |
| `x_url` | string(500) nullable | `null` | Enlace a X (Twitter). |
| `instagram_url` | string(500) nullable | `null` | Enlace a Instagram. |
| `window_day` | tinyint unsigned | `7` | Día de la ventana del culto (ISO-8601: 1=lunes … 7=domingo). |
| `window_start` | time | `09:00:00` | Inicio de la ventana horaria. |
| `window_end` | time | `13:00:00` | Fin de la ventana (intervalo semiabierto `[inicio, fin)`). |
| `mode` | string(20) | `auto` | Modo de visibilidad: `auto` \| `forzar_vivo` \| `forzar_oculto`. |
| `archivar` | boolean | `false` | Toggle para guardar/mostrar la grabación. |
| `url_grabacion` | string(500) nullable | `null` | URL de la grabación archivada. |
| `title` | string(255) nullable | `null` | Título mostrado del directo. |
| `active` | boolean | `true` | Interruptor general de la feature. |
| `created_at`/`updated_at` | timestamps | — | Marcas de tiempo. |

Valores por defecto del singleton (seeder `LiveStreamSeeder` y `current()`):
domingo 09:00–13:00, `platform=youtube`, `mode=auto`, `archivar=false`, `active=true`.

**Orden cronológico de migraciones (verificado):** la nueva migración
`2026_06_16_000000` queda después de la última de master
(`2026_06_15_000300_add_reference_and_verses_refs_to_verses_table`). Orden correcto.

---

## 2. Tabla de estados — lógica "¿en vivo?"

Resuelta en `App\Services\LiveStreamService::getLiveState()`. Precedencia:
**override manual (`mode`) → ventana horaria → YouTube Data API v3**. La clave de
YouTube nunca sale del servidor. El endpoint responde siempre HTTP 200.

| `mode` | ¿En ventana? | YouTube configurado (channel_id + api_key) | Respuesta API | `is_live` | `source` | Notas |
|---|---|---|---|---|---|---|
| `forzar_oculto` | — | — | — | `false` | `manual` | Corta de inmediato; no mira ventana ni llama a la API. |
| `forzar_vivo` | — | — | — | `true` | `manual` | Red de seguridad para redes sin API; no llama a la API. |
| `auto` | No | — | — | `false` | `window` | Fuera de ventana NO se llama a la API (ahorro de cuota). |
| `auto` | Sí | No (falta key o canal) | — | `true` | `window` | Fallback a ventana: en vivo por horario. |
| `auto` | Sí | Sí | Confirma directo (`video_id`) | `true` | `youtube_api` | Refina el `embed_url` con el `videoId` real. |
| `auto` | Sí | Sí | No en vivo / sin item | `true` | `window` | La API no apaga la señal de ventana; sigue en vivo por horario. |
| `auto` | Sí | Sí | Error/caída/timeout | `true` | `window` | Degrada sin excepción; cachea el fallo 30 s. |

Detalles: ventana evaluada en `config('app.timezone')`, intervalo semiabierto
`[window_start, window_end)`; detección YouTube vía `search.list`
(`eventType=live`) cacheada 3 min (positivo/negativo) y 30 s en fallo.

---

## 3. Contrato del endpoint `GET /api/live-status`

- **Ruta:** `GET /api/live-status` (nombre `api.live.status`), pública, sin auth.
- **Controlador:** `App\Http\Controllers\LiveController@status`.
- **Caché:** estado completo cacheado 3 min (`livestream:status`).
- **Seguridad:** nunca expone `YOUTUBE_API_KEY`. **Siempre** responde `200 OK`.
- **Content-Type:** `application/json`.

Cuerpo de respuesta (forma normalizada):

```json
{
  "is_live": true,
  "platform": "youtube",
  "embed_url": "https://www.youtube.com/embed/<videoId>?autoplay=1",
  "watch_urls": {
    "youtube":   "https://youtu.be/...",
    "facebook":  null,
    "tiktok":    null,
    "x":         null,
    "instagram": null
  },
  "title": "Culto dominical en vivo",
  "source": "youtube_api"
}
```

| Campo | Tipo | Descripción |
|---|---|---|
| `is_live` | bool | Verdadero si se emite ahora según la tabla de estados. |
| `platform` | string | Plataforma principal del embed (`youtube` \| `facebook`). |
| `embed_url` | string\|null | URL de reproducción incrustada; `null` si no procede (el front muestra solo enlaces). |
| `watch_urls` | object | Enlaces por red (`youtube`, `facebook`, `tiktok`, `x`, `instagram`), cada uno URL o `null`. |
| `title` | string\|null | Título del directo (de la API si existe, si no el configurado). |
| `source` | string | Origen de la decisión: `manual` \| `window` \| `youtube_api`. |

Para Facebook, `embed_url` usa el plugin de video
(`https://www.facebook.com/plugins/video.php?href=<url>&autoplay=1`).

---

## 4. YouTube API key y channel_id

La clave y el canal se leen SIEMPRE server-side vía `config('services.youtube.*)`
(ver `config/services.php`), alimentado por variables de entorno. **La clave nunca
se envía al navegador.** Guía completa para el cliente en `GUIA_TRANSMISION.md` §10.

Configuración (en el `.env` del servidor, nunca en el repositorio):

```env
YOUTUBE_API_KEY=AIza...tu_clave...
YOUTUBE_CHANNEL_ID=UC...tu_canal...
```

Cómo obtenerlas:

1. **API key** — En [Google Cloud Console](https://console.cloud.google.com) con la
   cuenta de Google de la iglesia: crear/seleccionar proyecto, habilitar **YouTube
   Data API v3**, ir a *APIs y servicios → Credenciales → Crear credenciales → Clave
   de API*. Copiar la clave (empieza por `AIza...`). Recomendado restringirla a la
   YouTube Data API v3.
2. **channel_id** — En YouTube Studio del canal: *Configuración → Configuración
   avanzada → ID del canal* (empieza por `UC...`).

Notas:
- La API key es **opcional**: sin ella la web funciona por horario (la ventana del
  domingo); la API solo hace la detección más precisa (`source=youtube_api`).
- El `YOUTUBE_CHANNEL_ID` también puede fijarse desde el panel (campo "ID de canal
  de YouTube"), que **tiene prioridad** sobre el valor del `.env`.
- Tras editar el `.env` en el servidor, limpiar caché de config
  (`php artisan optimize:clear`).

---

## 5. Lista de commits (`feat/culto-en-vivo` sobre `master` 8374ed5)

| SHA | Mensaje |
|---|---|
| `9a40b0f` | feat(culto-en-vivo): migracion, modelo singleton LiveStream y seeder |
| `1c06bd1` | feat(culto-en-vivo): servicio de estado y deteccion YouTube server-side |
| `acd7a9d` | feat(culto-en-vivo): endpoint proxy, pagina /en-vivo, banner y menu |
| `dc77632` | feat(culto-en-vivo): panel admin, override de un clic y archivado |
| `79d254a` | docs(culto-en-vivo): guia de transmision y nota de verificacion runtime |

(A estos se añade el commit `docs` con los artefactos de tareas y este reporte.)

---

## 6. Tabla de verificación

| Criterio de éxito | Resultado |
|---|---|
| Todo commiteado en `feat/culto-en-vivo` (nada sin commitear en el worktree) | OK — artefactos de `.agents/tasks` y este reporte commiteados; `git status` limpio. |
| Raíz `/Users/daragonp/Proyectos/eccav4` limpia (sin cambios en archivos trackeados) | OK — `git status --untracked-files=no` vacío; sin archivos de verificación sincronizados. |
| Rebase de `feat/culto-en-vivo` sobre `master` | OK — "up to date" (la rama ya parte de `8374ed5`, HEAD de master); sin conflictos. |
| Migraciones en orden cronológico tras el rebase | OK — `2026_06_16_000000` es la última, después de `2026_06_15_000300`. |
| Coherencia del código tras el rebase | OK — `php -l` sin errores en los 12 archivos PHP de la feature (vía contenedor, solo lectura). |
| Verificación runtime previa (migración, singleton, endpoints, regresión) | OK — documentada en `verificacion-runtime.md`; código idéntico al verificado (rebase no-op). |
| Tests de la feature | OK — `LiveStreamServiceTest` + `LiveStreamFeatureTest`: 23 tests, 67 assertions (según verificación previa). |
| Fallos de la suite completa | PRE-EXISTENTES — 13 fallos/errores, todos en `tests/Feature/Auth/*` (scaffolding Breeze, `UserFactory` ausente), ajenos a la feature. |
| `master` avanza por fast-forward a `feat/culto-en-vivo` | OK — `merge --ff-only` sin problemas (ver mensaje final). |
| Worktree NO borrado | OK — intacto (lo limpia el orquestador). |
| Sin `push` | OK — no se ejecutó ningún push. |
| Contenedores no reiniciados | OK — solo `docker exec`/`inspect` de solo lectura sobre `laravel_app`. |

---

_Nota: la verificación runtime no se repitió porque el contenedor `laravel_app`
monta solo la raíz (clean) y el rebase fue no-op; re-sincronizar los archivos a la
raíz habría reintroducido exactamente los artefactos que este paso debe mantener
limpios. Se validó coherencia con `php -l` sobre los archivos del worktree._
