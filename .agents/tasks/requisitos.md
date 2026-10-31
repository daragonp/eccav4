# Requisitos — Feature "Culto en Vivo"

Proyecto: ECCA v4 (Iglesia Emancipación Cristiana Afro) · Laravel · Worktree `feat/culto-en-vivo`

## Resumen

La iglesia celebra el culto dominical en directo y desea transmitirlo (audio y video) a través de la web. Hoy emiten por Google Meet para quienes no pueden asistir, y por la emisora solo sale el audio. La decisión acordada es emitir en vivo por **YouTube** (y de forma secundaria por **Facebook**, con posibilidad de **TikTok, X e Instagram**), y que la web active automáticamente una sección "Culto en Vivo" los domingos durante la ventana del culto.

La feature incluye: una configuración persistente del directo en base de datos (fila única / singleton), un servicio que determina "¿estamos en vivo ahora?" combinando una ventana horaria editable, un override manual y la detección automática por la API de YouTube; un banner condicional en la home; una página pública `/en-vivo` con reproductor embebido; un panel de administración para editar la configuración y activar/finalizar la transmisión de un clic; una opción de archivado de la grabación como culto; y un documento de operación para la persona que maneje la transmisión (OBS + doble cámara).

Decisión realista central que atraviesa todo el diseño: **solo YouTube ofrece una API fiable para autodetectar el estado "en vivo".** Facebook, TikTok, X e Instagram no tienen una API pública confiable de "¿estoy emitiendo?", por lo que esas plataformas se rigen por **ventana horaria + override manual**, nunca por autodetección.

### Suposiciones (a validar en la revisión de diseño)

1. La última migración real en `database/migrations/` es `2026_06_15_000300_add_reference_and_verses_refs_to_verses_table.php`. **La migración `2026_10_29_111024_create_job_batches_table` mencionada en la especificación NO existe en este worktree.** Por tanto la nueva migración debe llevar un timestamp posterior a `2026_06_15_000300` (p.ej. `2026_06_16_000000_*` o superior) para ordenarse al final; el diseño fijará el nombre exacto. Se asume que no hay migraciones pendientes de otra rama que deban anteponerse.
2. La configuración del directo es un **singleton** (una sola fila), coherente con que existe un único culto dominical. Se expondrá vía método estático `::current()` con `firstOrCreate` y/o seeder de fila por defecto.
3. La zona horaria de evaluación de la ventana es `config('app.timezone')`, que vale `America/Bogota`. No se hardcodea en el código de la ventana.
4. La plataforma principal incrustable por defecto es `youtube`. Facebook es incrustable como alternativa; TikTok, X e Instagram se ofrecen solo como enlaces externos "Ver también en…".
5. El menú público se edita en `resources/views/layouts/menu.blade.php` (es el que incluye `layouts.main`; `nav.blade.php` existe pero no es el usado por la home). El enlace del panel va en el nav-group **"Radio & Emisión"** ya existente en `resources/views/layouts/panel.blade.php`.
6. El patrón admin del proyecto usa rutas `/show-X`, `/addX`/`/update-X/{id}`, vistas en `resources/views/admin/<seccion>/` extendiendo `layouts.panel`, dentro de `Route::group(['middleware' => ['auth','admin']])`. Para un singleton se usa una única vista de ajustes con formulario que guarda (no CRUD de lista). Se asume ruta `/show-live` para la pantalla de ajustes; el diseño confirmará el nombre.
7. La clave de la API de YouTube y el `channel_id` se añaden como variables de entorno nuevas y como bloque `youtube` en `config/services.php`, replicando el patrón `widestream`. Nunca se usa `env()` directo fuera de config, ni se expone la key al navegador.
8. El modelo existente `Worship` (tabla con soft deletes; campos `title, slug, abstract, badge, broadcast, pdfdoc, autor, image, audio, video, urlyt, ai_*`) es el destino del archivado. El campo de fecha del culto es `broadcast` (cast `date`) y el enlace de video puede ir en `video`/`urlyt`.

## Requisitos funcionales

### RF-1 — Configuración del directo (modelo de datos)

- RF-1.1 Debe existir una configuración persistente del "culto en vivo" como fila única (singleton) en base de datos, con migración nueva y modelo nuevo (p.ej. `LiveSetting`/`LiveStream`) con casts apropiados.
- RF-1.2 La configuración debe almacenar: plataforma principal incrustable (`youtube` | `facebook`); identificadores/URLs por plataforma (p.ej. `youtube_channel_id` y/o `youtube_video_id` o `youtube_url`, `facebook_url`, `tiktok_url`, `x_url`, `instagram_url`); las URLs vacías no se muestran en la parte pública.
- RF-1.3 Debe almacenar una **ventana horaria editable** (no hardcodeada): día de la semana (domingo por defecto), hora de inicio (09:00 por defecto) y hora de fin (p.ej. 13:00 por defecto).
- RF-1.4 Debe almacenar un **modo de visibilidad/override** con tres valores: `auto`, `forzar_vivo`, `forzar_oculto`.
- RF-1.5 Debe almacenar un toggle `archivar` (booleano) y una `url_grabacion` opcional (nullable).
- RF-1.6 Debe exponer un acceso de conveniencia (método estático `::current()`) que devuelva la fila única, creándola con valores por defecto si no existe (firstOrCreate y/o seeder).
- RF-1.7 La configuración debe seguir las convenciones del proyecto (campo `active`/`updated_at`, soft deletes si aplica según el patrón de otras entidades).

### RF-2 — Servicio "¿en vivo ahora?" (`App\Services\LiveStreamService`)

- RF-2.1 Debe existir un servicio con `isLive(): bool` y `getLiveState(): array`.
- RF-2.2 Debe respetar el modo override: `forzar_oculto` → nunca en vivo; `forzar_vivo` → siempre en vivo hasta cambiar (red de seguridad manual para Facebook/TikTok/X/Instagram que no tienen API).
- RF-2.3 En modo `auto`, primero evalúa la **ventana horaria** (día + inicio/fin en `config('app.timezone')`). Fuera de la ventana → NO en vivo y **no se llama a la API de YouTube** (ahorro de cuota).
- RF-2.4 En modo `auto` y dentro de ventana, con YouTube configurado → consulta la API de YouTube; si indica directo → en vivo con el `videoId` para el embed.
- RF-2.5 Fallback dentro de ventana: si la API de YouTube falla, no hay key configurada, o no hay YouTube configurado → se considera "en vivo" por estar dentro de la ventana (señal pensada para las redes sin API). Esta decisión debe quedar documentada en el código.
- RF-2.6 `getLiveState()` debe devolver una estructura con al menos: `is_live`, `platform`, `embed_url`, `watch_urls` (`youtube`, `facebook`, `tiktok`, `x`, `instagram`), `title` (opcional) y `source` (`youtube_api` | `window` | `manual`).
- RF-2.7 El diseño debe incluir una **tabla de estados** que resuelva sin ambigüedad cada combinación de modo × ventana × resultado de API × plataforma.

### RF-3 — Detección por API de YouTube

- RF-3.1 Solo YouTube se autodetecta. Facebook, TikTok, X e Instagram se rigen por ventana + override manual.
- RF-3.2 Se usa YouTube Data API v3 (`search` con `part=snippet`, `channelId`, `eventType=live`, `type=video`, `key`) para detectar directo y obtener el `videoId`.
- RF-3.3 La llamada a la API solo ocurre **dentro de la ventana** y su resultado se **cachea** (2–5 min) para no agotar la cuota (la búsqueda cuesta ~100 unidades).
- RF-3.4 Con directo detectado, el embed es `https://www.youtube.com/embed/{videoId}?autoplay=1`.
- RF-3.5 La key vive en `config('services.youtube.api_key')` (env `YOUTUBE_API_KEY`); el `channel_id` igualmente vía config/env. Si falta la key, el sistema degrada a solo-ventana sin romper.
- RF-3.6 El navegador nunca recibe la key: existe un **endpoint proxy server-side** `GET /api/live-status` (cacheado) que el banner y la página consultan por `fetch`, replicando el patrón de `AdminController@nowPlaying` (WideStream).

### RF-4 — Banner en la home

- RF-4.1 Debe existir un partial nuevo `resources/views/partials/live-banner.blade.php`.
- RF-4.2 El partial se incluye al **inicio** del `@section('content')` de `welcome.blade.php`.
- RF-4.3 El partial hace `fetch` a `/api/live-status` al cargar y se muestra solo si `is_live` es verdadero, de modo que un visitante que ya está en la home ve aparecer el banner cuando empieza el culto.
- RF-4.4 El banner es llamativo ("EN VIVO AHORA — Culto Dominical") con botón "Ver ahora" que lleva a `/en-vivo`, con el indicador animado "EN VIVO" reutilizando el estilo ya existente en el proyecto (pulso emerald del topbar/panel) y Tailwind de marca.

### RF-5 — Página pública `/en-vivo`

- RF-5.1 Debe existir la ruta pública `GET /en-vivo` con name `live.index`, extendiendo `layouts.main` y con Tailwind de marca. Es una ruta nueva; no colisiona con la vieja `/live` (`HomeContentController@liveverse`), que no se toca.
- RF-5.2 Con `is_live=true` muestra el reproductor embebido de la plataforma principal (iframe de YouTube o Facebook de altura fija, coherente con `player.blade.php`/`radio-widget.blade.php`).
- RF-5.3 Muestra botones "Ver también en Facebook/TikTok/X/Instagram" para las redes configuradas (como enlace externo, al no ser incrustables de forma fiable).
- RF-5.4 Con `is_live=false` muestra el estado "No hay transmisión en vivo en este momento", con enlace opcional a la última grabación (si existe `url_grabacion`) o a la sección de cultos (`/worship`).

### RF-6 — Enlace en el menú público

- RF-6.1 Debe añadirse un enlace "En Vivo" al menú público en `resources/views/layouts/menu.blade.php`, tanto en la versión de escritorio como en la móvil, siguiendo las clases existentes (`nav-link`/`mobile-nav-link` con marcado de activo).

### RF-7 — Panel de administración

- RF-7.1 Debe existir una sección admin protegida por `['auth','admin']` (ruta p.ej. `/show-live`) con controlador nuevo y vista en `resources/views/admin/live/` extendiendo `layouts.panel`.
- RF-7.2 El formulario permite editar toda la configuración: URLs por plataforma, plataforma principal, ventana horaria (día/inicio/fin), modo (`auto`/`forzar_vivo`/`forzar_oculto` con selector claro), toggle `archivar` + `url_grabacion`.
- RF-7.3 La validación es en español y guardar actualiza el singleton (no crea filas nuevas).
- RF-7.4 Debe existir un **botón rápido de override** ("Estamos EN VIVO ahora" / "Finalizar transmisión") que alterne el modo entre `forzar_vivo` y `auto`/`forzar_oculto` en un clic, disponible en el panel/dashboard.
- RF-7.5 Debe añadirse un enlace de acceso a esta sección en el menú del panel, dentro del nav-group existente "Radio & Emisión" de `resources/views/layouts/panel.blade.php`.

### RF-8 — Archivado de la grabación

- RF-8.1 Al activar el toggle `archivar` y pegar la `url_grabacion` (video de YouTube tras el directo), la URL se guarda en la configuración.
- RF-8.2 Debe existir un botón explícito "Archivar como culto" que cree un registro `Worship` enlazado: fecha del culto (domingo) en `broadcast`, enlace del video, y título por defecto "Culto Dominical — <fecha>".
- RF-8.3 El archivado es manual asistido; no se automatiza la captura por API. El diseño decide el detalle de persistencia pero el botón explícito es lo preferido.

### RF-9 — Documento de operación

- RF-9.1 Debe crearse `GUIA_TRANSMISION.md` (raíz del worktree o `docs/`), en español, dirigido a una persona no técnica que opere la transmisión.
- RF-9.2 Debe cubrir: requisitos (PC, OBS Studio gratis, conexión estable, canal de YouTube verificado); cómo obtener la clave RTMP/stream key de YouTube Live y configurarla en OBS; configurar **dos cámaras** en OBS (fuentes, escenas, cambio), audio y escena básica (logo/título); ajustes recomendados de OBS (bitrate, resolución 720p/1080p, fps); el flujo del domingo (iniciar el directo en YouTube, verificar el banner, al terminar detener y archivar desde el panel); cómo obtener la **YouTube Data API KEY** en Google Cloud Console y dónde ponerla (`YOUTUBE_API_KEY` en el `.env` del servidor) junto con el `channel_id`, con pasos y enlaces oficiales; y qué hacer si transmiten por Facebook u otra red (usar el botón "Estamos EN VIVO ahora").

## Requisitos no funcionales

- RNF-1 **Seguridad de la key**: la `YOUTUBE_API_KEY` nunca se expone al navegador ni se versiona en el repo. En `.env.example` se añade `YOUTUBE_API_KEY=` (vacía) junto a las demás vars nuevas del bloque. El `.env` real no se toca salvo para documentar las vars nuevas.
- RNF-2 **No regresión**: no se rompe ninguna funcionalidad existente. No se tocan la API del versículo, el slider, la biblioteca ni otras secciones, salvo el enlace de archivado con `Worship`. La vieja `/live` no se modifica.
- RNF-3 **Cuota de API**: la API de YouTube solo se consulta dentro de la ventana y con caché de 2–5 min; `/api/live-status` también cachea su respuesta.
- RNF-4 **Convenciones del proyecto**: config vía `config/services.php` (bloque `youtube`), rutas y nombres coherentes con el patrón admin existente, validación en español, Tailwind de marca, iframes de altura fija, soft deletes si aplica, zona horaria `config('app.timezone')` = `America/Bogota`.
- RNF-5 **Operación en servidor compartido**: no se requiere reiniciar contenedores ni demonios; el despliegue debe funcionar en el hosting compartido donde conviven WordPress y este Laravel. No se hace push como parte de esta feature.

## Criterios de aceptación

> Alineados con los puntos de verificación de éxito exigidos por el usuario; el diseño y el plan deben respetarlos.

1. **Migración**: `php artisan migrate:status` muestra la nueva migración como `Ran`; la tabla y columnas de la configuración existen; se crea el singleton por defecto (vía seeder y/o `firstOrCreate`).
2. **Servicio — `forzar_oculto`**: con modo `forzar_oculto`, `isLive()` devuelve `false` (sin importar ventana ni API).
3. **Servicio — `forzar_vivo`**: con modo `forzar_vivo`, `isLive()` devuelve `true` y `getLiveState()` trae el `embed_url` correcto de la plataforma principal.
4. **Servicio — `auto` fuera de ventana**: con modo `auto` y fuera de la ventana horaria, `isLive()` devuelve `false` y **no se llama** a la API de YouTube.
5. **Servicio — `auto` dentro de ventana sin key**: con modo `auto`, dentro de ventana y sin `YOUTUBE_API_KEY`, el resultado es `is_live=true` con `source=window` (fallback a ventana), sin excepción.
6. **Endpoint estado**: `GET /api/live-status` responde `200` con JSON `{is_live, platform, embed_url, watch_urls, source, ...}` y su respuesta está cacheada.
7. **Página pública**: `GET /en-vivo` responde `200`; con `is_live=true` renderiza el iframe embebido; con `is_live=false` renderiza el mensaje "No hay transmisión en vivo en este momento".
8. **Banner en home**: `GET /` responde `200`; el partial del banner está incluido al inicio del contenido y hace `fetch` a `/api/live-status`.
9. **Panel protegido**: la ruta de ajustes (`/show-live` o el nombre elegido) responde `302` (redirección a login) **sin autenticación**, nunca `500`; autenticado, el formulario persiste los cambios en el singleton; el botón de override cambia el modo; los enlaces aparecen en el menú público y en el del panel.
10. **Archivado**: al archivar, la `url_grabacion` persiste; el botón "Archivar como culto" crea un registro `Worship` enlazado con fecha de domingo, video y título por defecto.
11. **Documento de operación**: `GUIA_TRANSMISION.md` existe, está en español y cubre todos los puntos de RF-9.
12. **Sin regresiones**: `/`, `/about`, `/biblia`, `/worship`, `/donate`, `/library` y `/en-vivo` responden `200` (los que apliquen); la API del versículo responde `401` sin token; el modal universal del panel queda intacto; el dock/reproductor de WideStream (`player.blade.php`) queda intacto.

## Fuera de alcance

- Automatizar la captura o descarga de la grabación por API (el archivado es manual asistido).
- Detección automática de "en vivo" para Facebook, TikTok, X o Instagram (no hay API fiable; se usan ventana + override manual).
- Reproductor embebido para TikTok, X o Instagram (solo enlaces externos).
- Modificar la vieja ruta `/live` (`HomeContentController@liveverse`), la API del versículo, el slider, la biblioteca u otras secciones, salvo el enlace de archivado con `Worship`.
- Cambios de infraestructura del servidor, reinicio de contenedores/demonios y el `git push`/despliegue (quedan fuera de esta feature).
- Decisiones de implementación detallada (estructura concreta de tablas, nombres finales de rutas/clases, manejo de errores por operación): corresponden al documento de diseño.
