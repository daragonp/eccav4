# Verificación runtime — Feature "Culto en Vivo"

Worktree NO montado en Docker. Procedimiento: sincronizar los archivos a la raíz
`/Users/daragonp/Proyectos/eccav4` (montada como `/app` en `laravel_app`), probar,
y restaurar la raíz dejándola limpia. La versión final queda commiteada SOLO en el
worktree (`feat/culto-en-vivo`). Comandos ejecutados y resultados:

## 1. Migración y caché
```
docker exec laravel_app php artisan migrate --force
  → 2026_06_16_000000_create_live_streams_table ... DONE (70ms)
docker exec laravel_app php artisan optimize:clear
  → config/cache/compiled/events/routes/views cleared
docker exec laravel_app php artisan migrate:status | grep live_streams
  → 2026_06_16_000000_create_live_streams_table ... [6] Ran
```

## 2. Singleton (tinker)
```
LiveStream::current() → mode=auto day=7 start=09:00:00 active=1 count=1
Segunda llamada NO crea otra fila (count=1).
```

## 3. Servicio — tres modos (tinker)
```
forzar_oculto               → isLive()=false
forzar_vivo (youtu.be/...)  → is_live=true, embed=https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1
auto fuera de ventana       → isLive()=false  (sin llamar API; ventana day=1 09:00–09:01)
auto dentro sin key         → is_live=true, source=window  (fallback a ventana, sin excepción)
```

## 4. Endpoints (curl, host → localhost:8080)
```
GET /api/live-status  → 200  JSON {is_live,platform,embed_url,watch_urls,title,source}
GET /en-vivo          → 200
GET /                 → 200
GET /en-vivo (forzar_vivo + URL YouTube) → contiene iframe youtube.com/embed/dQw4w9WgXcQ
GET /                 → contiene el fetch a /api/live-status (banner incluido)
GET /en-vivo (forzar_oculto) → contiene "No hay transmisión en vivo en este momento"
GET /show-live (sin auth)    → 302 (redirección a login, nunca 500)
```

## 5. Regresión (curl) y API privada
```
/        → 200
/about   → 200
/biblia  → 200
/worship → 200
/donate  → 200
/library → 200
/en-vivo → 200
/api/v1/verse/today (sin token) → 401
```
Dock/reproductor de WideStream y modal universal del panel: NO modificados.

## 6. Tests (phpunit dentro del contenedor; `artisan test` no está definido en este repo)
```
docker exec laravel_app php vendor/bin/phpunit --filter 'LiveStreamServiceTest|LiveStreamFeatureTest'
  → OK (23 tests, 67 assertions)
docker exec laravel_app php vendor/bin/phpunit   (suite completa)
  → Tests: 95, Assertions: 235, Errors: 11, Failures: 2
```
Los 13 fallos/errores restantes son PRE-EXISTENTES y están TODOS en
`tests/Feature/Auth/*` (scaffolding de Breeze): `Database\Factories\UserFactory`
no existe en el proyecto y el registro está deshabilitado (404). No tocan ningún
archivo de esta feature y fallan con o sin el cambio.

## 7. Restauración de la raíz
Archivos trackeados modificados → `git checkout --` (Banner.php, config/services.php,
DatabaseSeeder.php, dashboard/menu/panel/welcome.blade.php, routes/web.php, .env.example).
Archivos nuevos copiados → `rm` (migración, LiveStream, seeder, servicio, controladores,
vistas live/admin.live/partials, tests, YouTube helper, GUIA_TRANSMISION.md).
También se eliminó `images/bible/` (caché generada por el curl a /biblia, no de la feature).
`git status --short` de la raíz: limpio (solo .agents/ y .worktrees/ preexistentes).
La tabla `live_streams` y su singleton quedan en la BD compartida `laravel_db` a
propósito (la migración no se revierte).
```
```
