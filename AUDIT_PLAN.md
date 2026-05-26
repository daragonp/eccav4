# Auditoría y plan de seguridad para eccav4

## Objetivo
Definir acciones claras para corregir riesgos de configuración, dependencias y seguridad en el proyecto.

## Pasos

### 1. Corregir configuración de entorno
- Asegurar que `APP_DEBUG=false` en despliegues de producción.
- Quitar `.env` de entornos compartidos.
- Usar variables de entorno del servidor o CI en lugar de `.env` en producción.
- Rotar credenciales expuestas si el archivo `.env` fue accesible a terceros.
- Mantener `.env.example` en el repositorio con valores de ejemplo.

### 2. Mejorar CORS
- Evitar `allowed_origins: ['*']` en producción.
- Usar `CORS_ALLOWED_ORIGINS` y `CORS_ALLOWED_METHODS` para restringir orígenes y métodos.
- Mantener `supports_credentials` en `false` a menos que el frontend lo requiera explícitamente.

### 3. Actualizar dependencias
#### NPM
- `axios`
- `lodash`
- `vite`
- `@hotwired/turbo`
- `@fortawesome/fontawesome-free`
- `datatables.net`
- `datatables.net-dt`
- `datatables.net-responsive`
- `datatables.net-responsive-dt`
- `jquery`
- `lightbox2`
- `sweetalert2`

#### Composer
- `barryvdh/laravel-dompdf`
- `laravel/framework`
- `laravel/sanctum`
- `laravel/tinker`
- `spatie/laravel-permission`
- `stevebauman/location`
- `yajra/laravel-datatables`

### 4. Validar y probar
- `composer validate`
- `composer outdated --direct`
- `npm audit`
- `npm outdated`
- Ejecutar pruebas de rutas y formularios críticos.
- Revisar APIs `api/*`, CSRF y permisos de administrador.

## Comandos sugeridos
```bash
composer validate --no-check-publish
composer outdated --direct
npm audit
npm outdated
```

## Notas
- Se ha añadido `.env.example` con valores de ejemplo.
- La configuración CORS ahora puede restringirse vía variables de entorno.
