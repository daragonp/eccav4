# Reporte — API privada "Versículo del día" (feat/api-versiculo)

Feature que expone el versículo del día de ECCA a servicios externos autorizados
(la emisora de radio) mediante un endpoint privado protegido por API key, con
registro de estadísticas de uso. Incluye además el nuevo campo de texto obligatorio
del versículo y el panel de administración de claves API.

---

## 1. Esquema de datos

### 1.1 `verses` (columna añadida)

| Columna | Tipo | Notas |
|---------|------|-------|
| `text`  | `TEXT` nullable (tras `date`) | Texto del versículo. `NULL` a nivel de BD para no romper filas históricas; **obligatorio** en la capa de aplicación (validación en `VerseController@store` y `@edit`). Las filas existentes quedan con `text = NULL` y el detalle admin muestra un aviso ámbar para completarlas. |

Migración: `database/migrations/2026_06_15_000000_add_text_to_verses_table.php`.

### 1.2 `api_clients`

| Columna | Tipo | Notas |
|---------|------|-------|
| `id` | `bigIncrements` | PK |
| `name` | `string(120)` | Nombre del servicio/emisora |
| `token` | `string(64)` unique | **Hash sha256** del token (64 hex). Nunca el token en claro. |
| `token_prefix` | `string(16)` | Primeros 9 caracteres del token (`ecca_xxxx`) para identificarlo en la UI |
| `active` | `boolean` default `true` | Habilita/revoca el cliente |
| `last_used_at` | `timestamp` nullable | Última autenticación exitosa |
| `created_at` / `updated_at` | `timestamps` | |
| `deleted_at` | `softDeletes` | Archivar sin perder histórico |

Índice: `['active','deleted_at']` (lookup de autenticación).
Migración: `database/migrations/2026_06_15_000100_create_api_clients_table.php`.

### 1.3 `api_request_logs`

| Columna | Tipo | Notas |
|---------|------|-------|
| `id` | `bigIncrements` | PK |
| `api_client_id` | `foreignId` nullable (`nullOnDelete`) | `NULL` en peticiones no autorizadas |
| `endpoint` | `string(190)` | Ruta solicitada (p. ej. `api/v1/verse/today`) |
| `method` | `string(10)` | Verbo HTTP |
| `ip` | `string(45)` | IPv4/IPv6 |
| `user_agent` | `string(255)` nullable | Truncado a 255 |
| `status_code` | `unsignedSmallInteger` | Código HTTP resultante |
| `authorized` | `boolean` default `false` | `true` si autenticó correctamente |
| `created_at` | `timestamp` nullable | Log inmutable (solo `created_at`) |

Índices: `api_client_id`, `created_at`, `['authorized','created_at']`.
Migración: `database/migrations/2026_06_15_000200_create_api_request_logs_table.php`.

**El token nunca se guarda en los logs** (ni en claro ni en hash). La trazabilidad
del cliente se hace por `api_client_id`.

---

## 2. Contrato del endpoint

**Método y URL**
```
GET http://localhost:8080/api/v1/verse/today
```

**Cabeceras**
```
Authorization: Bearer ecca_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
Accept: application/json
```
Alternativa aceptada: `X-API-Key: ecca_xxxx...` en lugar de `Authorization`.

**Protección:** middleware `auth.apiclient` + `throttle:60,1` (60 peticiones/minuto por IP).
El throttle global del grupo `api` se desactiva para estas rutas
(`withoutMiddleware('throttle:api')`) y el orden `['auth.apiclient','throttle:60,1']`
es intencional: `auth.apiclient` va primero para registrar TODA petición, incluidos
los 429 del throttle de ruta.

### 200 OK
```bash
curl -s -H "Authorization: Bearer ecca_XXXX..." -H "Accept: application/json" \
  http://localhost:8080/api/v1/verse/today
```
```json
{
  "data": {
    "id": 2,
    "date": "2026-10-29",
    "text": "Texto de prueba (Juan 3:16)",
    "image_url": "http://localhost:8080/images/bible/qa-today.jpg",
    "audio_url": null,
    "pdf_url": null,
    "created_at": "2026-10-29T08:28:59-05:00",
    "updated_at": "2026-10-29T08:28:59-05:00"
  }
}
```
`text` puede ser `null` si el versículo aún no se ha completado. `image_url`,
`audio_url` y `pdf_url` son URLs absolutas (o `null`), generadas por los accessors
del modelo `Verse`. `pdf_url` proviene de la columna `video` (documents/quote/).

### 401 Unauthorized
Dos cuerpos según el caso:
```bash
# (a) Sin cabecera de autenticación
curl -s http://localhost:8080/api/v1/verse/today
```
```json
{ "message": "No autorizado. Falta el token de autenticación." }
```
```bash
# (b) Token inválido o cliente inactivo/archivado
curl -s -H "Authorization: Bearer token_malo" http://localhost:8080/api/v1/verse/today
```
```json
{ "message": "No autorizado. Token inválido o cliente inactivo." }
```
El caso (b) no distingue "inválido" de "inactivo" para no filtrar información.

### 404 Not Found (no hay versículo para hoy)
```bash
curl -s -H "Authorization: Bearer ecca_XXXX..." http://localhost:8080/api/v1/verse/today
```
```json
{ "message": "No hay versículo disponible para hoy." }
```
404 explícito, **sin respaldo** del versículo más reciente (decisión del usuario).

### 429 Too Many Requests
Respuesta estándar de Laravel al superar el `throttle:60,1`.

---

## 3. Generación y revocación de API keys

**Generación (panel admin):**
1. Menú lateral → "Sistema & Soporte" → **Claves de API** (`/show-api-clients`).
2. Botón "Nuevo cliente" → se indica el nombre del servicio/emisora.
3. Al crear, se genera un token en claro `ecca_` + 40 caracteres aleatorios
   (`Str::random(40)`). En BD se guarda `hash('sha256', $token)` y un prefijo
   identificable (`token_prefix`, primeros 9 caracteres).
4. El token en claro **se muestra una sola vez** en una tarjeta destacada con botón
   "Copiar". No se persiste y no es recuperable.

Lógica encapsulada en `App\Models\ApiClient::issue(string $name): array`
(devuelve `['client' => ..., 'plain' => ...]`), testeable sin HTTP.

**Revocación / archivado:**
- **Revocar** (`POST /toggle-api-client/{id}`): invierte `active`. Con `active=false`
  el middleware rechaza con 401. Reversible (se puede reactivar).
- **Archivar** (`DELETE /delete-api-client/{id}`): soft delete. El cliente deja de
  autenticar (el lookup exige no-trashed y `active=true`) pero conserva el histórico.

**Por qué sha256 y no bcrypt:** el token es de alta entropía (40 chars aleatorios),
por lo que un hash rápido determinista permite el lookup por índice único
`WHERE token = ?` en una sola consulta, sin enumerar clientes.

---

## 4. Tabla de verificación HTTP (runtime)

Verificado contra `http://localhost:8080` (contenedor `laravel_app`), tras sincronizar
los archivos a la raíz, `migrate --force` y `optimize:clear`. Datos de prueba sembrados
y luego eliminados (ver §5).

| # | Prueba | Comando (resumen) | Resultado |
|---|--------|-------------------|-----------|
| 1 | Migraciones nuevas | `migrate:status \| grep 2026_06_15` | Las 3 → **Ran** |
| 2 | Esquema | `Schema::hasColumn/hasTable` | `verses.text`=SI, `api_clients`=SI, `api_request_logs`=SI |
| 3 | Token en BD = hash | `ApiClient::issue(...)` | `token` en BD == `sha256(plain)`, prefijo `ecca_Ord7` |
| 4 | 401 sin token | `curl -o /dev/null -w %{http_code}` | **401** |
| 5 | 401 body sin token | `curl` | `"No autorizado. Falta el token de autenticación."` |
| 6 | 200 con `Authorization: Bearer` | `curl` | **200**, JSON con `text`, `image_url` absoluta, `date` |
| 7 | 200 con `X-API-Key` | `curl` | **200** |
| 8 | 401 token inválido | `curl` | **401** `"...Token inválido o cliente inactivo."` |
| 9 | 404 sin versículo de hoy | (mover fecha del versículo) + `curl` | **404** `"No hay versículo disponible para hoy."` |
| 10 | 401 cliente inactivo | (`active=false`) + `curl` | **401** |
| 11 | Registro de TODA petición | `ApiRequestLog::count()` antes/después | 0 → 7 (status: `401,200,200,401,401,200,404`); 4 autorizadas, 3 no |
| 12 | `last_used_at` actualizado | tinker | `2026-10-29 08:29:51` (tras autenticación exitosa) |
| 13 | `/show-api-clients` sin auth | `curl -o /dev/null -w %{http_code}` | **302** (no 500) |
| 14 | `/api-stats` sin auth | `curl` | **302** |
| 15 | `/show-quote` sigue protegido | `curl` | **302** |
| 16 | Blade compila (vistas nuevas + modificadas) | `php artisan view:cache` | **OK** |
| 17 | Sin regresiones públicas | `curl` a `/ /about /biblia /worship /donate /library` | Todas **200** |

**Validación del campo texto** (lógica verificada; reglas aplicadas en ambos métodos):
- `VerseController@store`: regla `text => required|string`, mensaje
  `"Debe ingresar el texto del versículo."`; persiste `$quote->text`.
- `VerseController@edit`: misma regla y mensaje; persiste `$quote->text`.
- El modal de edición universal (`sectionType==='verse'`) incluye el textarea con
  `{{ $tableM->text }}` precargado, imprescindible porque `text` ahora es `required`.
- `view-quote` muestra el texto (o aviso ámbar si está vacío).

---

## 5. Datos de prueba y limpieza

Sembrado para las pruebas:
```bash
# Cliente de prueba
docker exec laravel_app php artisan tinker --execute="\$r=App\Models\ApiClient::issue('Emisora QA'); echo \$r['plain'];"
# Versículo de hoy con texto
docker exec laravel_app php artisan tinker --execute="App\Models\Verse::create(['image'=>'qa-today.jpg','date'=>today()->toDateString(),'text'=>'Texto de prueba (Juan 3:16)']);"
```
Para probar el 404 se cambió la fecha del versículo sembrado a ayer.

Limpieza final (ejecutada): se eliminaron (`forceDelete`) el versículo `qa-today.jpg`,
el cliente "Emisora QA" y todos sus `api_request_logs`, dejando
`ApiRequestLog::count() = 0`. Las tablas y la columna creadas por las migraciones
persisten en la BD de desarrollo (volumen `laravel_db_data`), lo cual es esperado.

La raíz `/Users/daragonp/Proyectos/eccav4` se restauró a `master`
(`git status --short` solo muestra `.agents/` y `.worktrees/`, ajenos a la feature).
La versión final del código queda commiteada **solo** en el worktree
`feat/api-versiculo`.

---

## 6. Notas

- No se introdujeron variables de entorno nuevas ni credenciales en
  `config/services.php`: la generación de tokens es en tiempo de ejecución y no
  depende de servicios externos. Por tanto no se modificó `.env` ni `.env.example`.
- No se tocó `bootstrap/app.php`: el proyecto usa el skeleton clásico y el alias
  `auth.apiclient` se registró en `app/Http/Kernel.php → $routeMiddleware`, junto a
  `admin`/`superadmin`.
- `config/cors.php` ya cubre `api/*`; no requirió cambios.
