# Guía de Comandos - Entorno Local y Producción (Docker)

Esta guía contiene los comandos indispensables para administrar el proyecto tanto en tu entorno **local** (Mac + Docker) como en el **servidor en producción** (Linux + Docker).

---

## 1. Entorno Local (Tu Mac)

Dado que no tienes PHP instalado directamente en tu Mac, los comandos se dividen según dónde se ejecutan:

### 💻 En la Terminal de tu Mac (Assets, Git y Node)
Los archivos JS, CSS y de compilación se manejan en tu Mac usando Node.js/NPM:

* **Compilar assets para producción**:
  ```bash
  npm run build
  ```
* **Correr servidor de desarrollo local (Vite)**:
  ```bash
  npm run dev
  ```
* **Subir cambios a Git**:
  ```bash
  git add .
  git commit -m "Descripción de tus cambios"
  git push origin master
  ```

### 🐳 Dentro del Contenedor Docker Local (PHP, Laravel y Artisan)
Cualquier comando que involucre PHP o Laravel debe ser ejecutado dentro del contenedor de Docker `laravel_app`:

* **Ver lista de rutas**:
  ```bash
  docker exec -it laravel_app php artisan route:list
  ```
* **Ejecutar migraciones de base de datos**:
  ```bash
  docker exec -it laravel_app php artisan migrate
  ```
* **Limpiar toda la caché del sistema**:
  ```bash
  docker exec -it laravel_app php artisan optimize:clear
  ```
* **Entrar a la consola interactiva de Laravel (Tinker)**:
  ```bash
  docker exec -it laravel_app php artisan tinker
  ```
* **Instalar dependencias de Composer**:
  ```bash
  docker exec -it laravel_app composer install
  ```

---

## 2. Servidor en Producción

Para conectarte al servidor y aplicar los cambios que has programado y subido a Git:

### 🔑 Conectarse al Servidor
* **Acceso SSH**:
  ```bash
  ssh -l root -p 22 2.59.156.25
  # Contraseña: Jacobo2505*+
  ```

### 🚀 Despliegue de Cambios (Deploy)
Una vez dentro del servidor por SSH, sigue estos pasos en orden para descargar tu código de Git y aplicarlo de forma segura:

1. **Ir al directorio del proyecto**:
   ```bash
   cd /srv/laravel
   ```
2. **Descargar los últimos cambios de GitHub**:
   ```bash
   git pull
   ```
3. **Ejecutar el script de despliegue**:
   ```bash
   ./deploy.sh
   ```

### 🛠️ Comandos útiles en Producción (Docker Exec)
Si necesitas realizar tareas de mantenimiento directamente en el servidor sin reconstruir todo:

* **Ver estado de los contenedores**:
  ```bash
  docker ps
  ```
* **Ejecutar migraciones en producción**:
  ```bash
  docker exec -t laravel_app php artisan migrate --force
  ```
* **Re-generar la caché de Laravel (si algo no carga correctamente)**:
  ```bash
  docker exec -t laravel_app php artisan optimize:clear
  docker exec -t laravel_app php artisan config:cache
  docker exec -t laravel_app php artisan route:cache
  docker exec -t laravel_app php artisan view:cache
  ```
* **Entrar a Tinker en producción**:
  ```bash
  docker exec -it laravel_app php artisan tinker
  ```
* **Re-iniciar los contenedores**:
  ```bash
  docker compose -f docker-compose.production.yml restart
  ```
