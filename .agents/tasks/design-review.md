# Revisión de diseño — Feature "Culto en Vivo" (2.ª iteración)

Revisor: subagente de revisión de diseño (lectura en frío del documento + verificación contra el código real del worktree `feat/culto-en-vivo`).
Documento revisado: `.agents/tasks/diseno.md`
Requisitos: `.agents/tasks/requisitos.md`

## Contexto

Es la **segunda iteración**. El diseño declara haber resuelto los 8 hallazgos de la revisión previa (2 HIGH, 3 MEDIUM, 3 NIT). Esta revisión verifica una por una esas resoluciones contra el código y busca hallazgos nuevos. El veredicto es **mecánico**: cualquier HIGH o MEDIUM ⇒ `CHANGES_REQUESTED`; cero HIGH/MEDIUM ⇒ `APPROVED`.

---

## Hallazgos

### 1 [MEDIUM] — El archivado (`updateOrCreate`) ignora los `Worship` con *soft delete* y puede violar el UNIQUE de `title`

**Dónde:** §6, bloque `archive()` — bucle de desambiguación de `title` y `Worship::updateOrCreate(['broadcast' => $fecha], ...)`.

**Problema (verificado):** `app/Models/Worship.php` usa `use ... SoftDeletes;` y la tabla tiene `deleted_at` nullable (`2023_10_31_154704_create_worships_table.php`). El índice UNIQUE sigue vivo a nivel de BD aunque la fila esté *soft-deleted*. Pero:

- El bucle de desambiguación `Worship::where('title', $title)->where('broadcast','!=',$fecha)->exists()` usa el *scope* global por defecto, que **excluye los registros con `deleted_at`**. Un culto previamente archivado y luego "borrado" (soft delete) con ese mismo `title` **no se detecta**, así que el bucle cree que el título está libre.
- `updateOrCreate(['broadcast'=>$fecha], ...)` tampoco encuentra la fila *soft-deleted* (mismo *scope*), por lo que intenta un **`INSERT`**. Ese `INSERT` choca con el UNIQUE de `title` de la fila *soft-deleted* → excepción de integridad SQL 1364/duplicate key, 500 no previsto.

El diseño afirma en su tabla de errores §6 que "el bucle de desambiguación evita la colisión antes del INSERT; no llega excepción". Esa afirmación es **falsa en presencia de soft deletes**, que el propio diseño reconoce que `Worship` usa.

**Fix concreto:** decidir y documentar una de estas reglas, y reflejarla en el bucle y en el `updateOrCreate`:

- Opción A (recomendada): incluir los *trashed* en la detección y restauración:
  ```php
  // buscar incluyendo soft-deleted para ese domingo
  $existing = Worship::withTrashed()->where('broadcast', $fecha)->first();
  if ($existing && $existing->trashed()) { $existing->restore(); }
  // y el bucle de título también con withTrashed():
  while (Worship::withTrashed()->where('title', $title)
          ->where('broadcast','!=',$fecha)->exists()) { ... }
  ```
- Opción B: declarar explícitamente que el archivado solo opera sobre cultos no borrados y que un culto *soft-deleted* del mismo domingo es un caso no soportado (y entonces el bucle debe usar `withTrashed()` de todos modos para no romper el UNIQUE).

Sin esta corrección el criterio de aceptación 10 (archivar sin error) falla en el escenario de re-archivado tras borrado.

---

### 2 [NIT] — El snippet de `welcome.blade.php` en §4 no refleja el archivo real

**Dónde:** §4, bloque "Punto de inclusión".

**Problema (verificado):** el diseño muestra
```blade
@section('content')
  @include('partials.live-banner')
  <section aria-labelledby="home-quote" class="mb-8">
```
El archivo real tiene una **línea en blanco** entre `@section('content')` y `<section aria-labelledby="home-quote" ...>` (líneas 5–7 de `welcome.blade.php`). No cambia la instrucción ("al inicio del content, antes de la sección Palabra del día"), que es correcta, pero el snippet literal no calca el archivo. Riesgo bajo de que un `str_replace` exacto falle.

**Fix concreto:** anotar que la inserción va justo después de `@section('content')` y antes de la primera `<section aria-labelledby="home-quote">`, sin depender del espaciado exacto; o reproducir la línea en blanco real en el snippet.

---

### 3 [NIT] — `autor` del archivado difiere del valor que usa `WorshipController::store`

**Dónde:** §6, tabla de mapeo (`autor` = `'Culto en Vivo'`).

**Problema (verificado):** `WorshipController::store` usa por defecto `autor = 'P. Henry Belalcázar'`. El diseño elige `'Culto en Vivo'`. No es un bug (ambos caben en `string(30)` y el campo no es restrictivo), pero el diseño dice en §6 "con los mismos valores que usa `WorshipController::store`", lo cual es inexacto para `autor`. Coherencia documental, no funcional.

**Fix concreto:** aclarar que `autor` es un valor propio del archivado (no copiado de `store`), o alinear a `'P. Henry Belalcázar'` si se desea paridad.

---

## Verificaciones realizadas (Verified Assumptions)

Todo lo siguiente se comprobó leyendo el código real del worktree y **coincide** con lo que afirma el diseño:

1. **Restricciones de `worships`** (`2023_10_31_154704_create_worships_table.php`): `title` es `->unique()`; `slug` NO es único; `abstract` (`text`), `badge` (`text`), `pdfdoc` (`string`) son **NOT NULL sin default**; `image` tiene default `'generic.png'`; `audio`/`video`/`urlyt`/`deleted_at` nullable; `autor` es `string(30)`. El diseño describe esto correctamente (hallazgos previos 1 y 2 bien resueltos).
2. **`Banner::detectYoutubeId()`** (`app/Models/Banner.php`) es `protected` y contiene exactamente el regex citado por el diseño. La extracción a `App\Support\YouTube::extractId()` y la delegación de `Banner` son viables y correctas (hallazgo previo 3 bien resuelto).
3. **Patrón `AdminController@nowPlaying`**: usa `config('services.widestream.api_key')`, `Cache::remember(..., now()->addSeconds(10), ...)`, `Http::...->timeout(5)`, `response()->json($data, 200)` y degrada cuando la key está vacía. Vive bajo `Route::group(['middleware' => ['auth','admin']])`. El diseño replica el **mecanismo** server-side y expone la ruta como pública (como `/api/programa-actual`, que efectivamente es pública). Correcto (hallazgo previo 6 bien resuelto).
4. **`WorshipController::store` defaults**: `badge` default `'Culto Dominical'`, `abstract` default `''`, `pdfdoc = ''`. Coinciden con el mapeo del archivado (salvo `autor`, ver hallazgo 3).
5. **Última migración real** = `2026_06_15_000300_add_reference_and_verses_refs_to_verses_table.php`. El timestamp propuesto `2026_06_16_000000` se ordena correctamente al final. La migración `job_batches` mencionada por la spec no existe (como ya advertían los requisitos).
6. **`config/services.php`**: existe el bloque `widestream` con el patrón `env()`; añadir `youtube` análogo es trivial y coherente.
7. **`.env.example`**: contiene el bloque WideStream con `WIDESTREAM_API_KEY=` vacío y nota de no subir la clave real; añadir `YOUTUBE_API_KEY=`/`YOUTUBE_CHANNEL_ID=` junto a él es coherente (RNF-1).
8. **`routes/web.php`**: `/live` existe (`HomeContentController@liveverse`); `/en-vivo` está libre (sin colisión). `/api/programa-actual` es público; el grupo admin usa `['auth','admin']`.
9. **`layouts/menu.blade.php`**: existe el contenedor desktop `.flex items-center space-x-1` y el bloque móvil `.space-y-1`, con las clases `nav-link`/`nav-link-active` y `mobile-nav-link`/`mobile-nav-link-active`, y el enlace "Culto dominical" (`url('worship')`). La inserción propuesta encaja.
10. **`layouts/panel.blade.php`**: existe el nav-group "Radio & Emisión" (`nav-section-title`, ~línea 123) con "Cultos Dominicales" (`show-worship*`, patrón `nav-item group ... active`). El indicador emerald con pulso existe (~línea 299). Las referencias del diseño son correctas.
11. **`Worship`** (`app/Models/Worship.php`): `use SoftDeletes`, `broadcast` cast `date`, `$fillable` incluye `title, slug, abstract, badge, broadcast, pdfdoc, autor, image, audio, video, urlyt` — todos los campos mapeados por el archivado son `fillable`.
12. **Tabla de estados (§2)**: cubre las 8 combinaciones relevantes (3 modos; en modo `auto`: fuera de ventana; dentro × YT no-config por channel / no-config por key / config con API en-vivo / no-en-vivo / falla). El fallback "dentro de ventana ⇒ `is_live=true, source=window`" está definido sin ambigüedad y la regla de borde (`[inicio, fin)`, normalización `substr(...,0,5)`) es determinista. Completa y sin ambigüedad.
13. **Contrato `GET /api/live-status`**: ruta, name (`api.live.status`), ubicación pública, controlador/acción, caché (`Cache::remember('livestream:status', 3 min)`), JSON de respuesta y "key nunca en la respuesta" están completamente especificados. La key queda server-side y fuera del repo (placeholder en `.env.example`). Cumple RF-3.6 y RNF-1.
14. **Modelo singleton**: columnas/tipos/nulabilidad/defaults/casts tabulados; `::current()` con `firstOrCreate`; seeder + registro en `DatabaseSeeder`; migración con timestamp posterior. Completo (RF-1).
15. **Cuota y caché de YouTube**: `search.list` ≈ 100 unidades documentado, caché 3 min, llamada solo dentro de ventana, caché de fallo 30 s, degradación sin key definida (casos 4/5). Cumple RF-3.3/RF-3.5/RNF-3.
16. **"Solo YouTube se autodetecta"**: explícito y realista; el resto por ventana + override manual, con `forzar_vivo` como red de seguridad. Cumple RF-3.1/RF-2.2.
17. **Lista de archivos y variables de entorno**: 11 a crear + 9 a modificar, con rutas absolutas; vars nuevas `YOUTUBE_API_KEY`/`YOUTUBE_CHANNEL_ID`. Completa y coherente con el resto del diseño.

## Supuestos no verificados o incorrectos (Unverified/Wrong Assumptions)

- **Incorrecto (hallazgo 1):** la afirmación de §6 de que "el bucle de desambiguación evita la colisión antes del INSERT; no llega excepción" **no se sostiene** cuando existe un `Worship` *soft-deleted* con el mismo `title` o `broadcast`, porque el bucle y `updateOrCreate` usan el *scope* por defecto que excluye *trashed*, mientras que el UNIQUE de `title` sigue activo en BD.
- **Inexacto (hallazgo 3):** "con los mismos valores que usa `WorshipController::store`" no aplica a `autor` (`'Culto en Vivo'` vs `'P. Henry Belalcázar'`).
- **No verificado (no bloqueante):** el comportamiento real del plugin de video de Facebook (`plugins/video.php`) para directos sin SDK/dominio no se puede verificar en frío; el diseño ya lo trata como riesgo y ofrece degradación a enlace externo, lo cual es una mitigación aceptable.
- **No verificado (no bloqueante):** la cuota exacta de `search.list` (~100 u.) procede de la documentación pública de Google, no del código; el diseño la usa solo para dimensionar el peor caso, que queda holgado dentro de 10.000 u./día.

---

## Veredicto

Hallazgos HIGH: 0 · Hallazgos MEDIUM: 1 · Hallazgos NIT: 2.

Como hay 1 MEDIUM, el veredicto mecánico es **CHANGES_REQUESTED**. El único bloqueante real es el hallazgo 1 (interacción del archivado con *soft deletes* y el UNIQUE de `title`); resolverlo deja el diseño listo para implementación. Los dos NIT son de coherencia documental y no bloquean por sí mismos, pero conviene corregirlos en la misma pasada.
