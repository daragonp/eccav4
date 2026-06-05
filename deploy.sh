#!/usr/bin/env bash
set -e

APP_DIR="/srv/laravel"
COMPOSE="docker compose -f docker-compose.production.yml"
APP_SERVICE="laravel_app"
APP_WORKDIR="/app"
BRANCH="master"

cd "$APP_DIR"

echo "==> Levantando contenedores base"
$COMPOSE up -d --force-recreate --remove-orphans

echo "==> Activando modo mantenimiento"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan down || true

echo "==> Instalando dependencias PHP"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "==> Limpiando caches"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan optimize:clear

echo "==> Cacheando Laravel"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan config:cache || true
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan route:cache || true
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan view:cache || true

echo "==> Ejecutando migraciones"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan migrate --force

echo "==> Storage link"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan storage:link || true

echo "==> Saliendo de mantenimiento"
$COMPOSE exec -T -w $APP_WORKDIR $APP_SERVICE php artisan up || true

echo "==> Deploy completado"
