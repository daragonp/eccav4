# Cron de producción — Procesamiento de colas (ECCA v4)

El sitio usa `QUEUE_CONNECTION=database` en producción (hosting compartido StackCP,
sin Redis ni workers de larga duración). Los trabajos encolados —procesamiento de
audio con IA al subir un culto, y geolocalización de suscriptores— se guardan en la
tabla `jobs` y **necesitan un cron que los procese**; de lo contrario quedan pendientes
sin ejecutarse.

## Datos del entorno (verificados)

- Ruta del proyecto: `/home/sites/42b/c/cf4f0cbd5c/laravel_app`
- Binario PHP: `/usr/bin/php84` (PHP 8.4)
- El `crontab` por SSH está deshabilitado: los cron jobs se crean desde el
  **panel de control de StackCP/20i** (sección "Cron Jobs" / "Tareas programadas").

## Cron a crear en el panel de StackCP

Frecuencia: **cada 1 minuto** (o cada 5 minutos si el panel no permite 1 min).

Comando:

```
/usr/bin/php84 /home/sites/42b/c/cf4f0cbd5c/laravel_app/artisan queue:work --stop-when-empty --max-time=55 >> /home/sites/42b/c/cf4f0cbd5c/laravel_app/storage/logs/queue-cron.log 2>&1
```

Notas:
- `--stop-when-empty`: procesa los trabajos pendientes y termina (apto para cron en
  hosting compartido; NO usar `queue:work` sin esta bandera, que correría indefinidamente).
- `--max-time=55`: límite de seguridad para que no solape con la siguiente ejecución
  si hubiera muchos trabajos.
- La salida se registra en `storage/logs/queue-cron.log` para diagnóstico.

## (Opcional) Reintentar trabajos fallidos

Si algún trabajo falla queda en `failed_jobs`. Para reintentarlos manualmente por SSH:

```
php84 artisan queue:retry all
```

## Verificación

- Jobs pendientes:    `php84 artisan tinker --execute="echo \DB::table('jobs')->count();"`
- Jobs fallidos:      `php84 artisan queue:failed`
- Procesar a mano:    `php84 artisan queue:work --stop-when-empty`
