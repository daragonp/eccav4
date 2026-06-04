![logoshine](https://github.com/daragonp/eccav4/blob/a42f9634a77406ac38d1e16d5e2106947f917419/public/images/logo/logo.png)


Palabra de vida para el pueblo de tez brillante. Una mirada desde el cielo de los hijos, de la diáspora africana. Biblia, solo Biblia; el dedo índice en la palabra de Dios.
<p align="center" style="font-weight: 400">
##Iglesia Emancipación Cristiana Afro - ECCA
</p>

## Deploy automático

Este proyecto se despliega automáticamente a producción mediante GitHub Actions cuando hay un push a la rama `master`.

### Flujo

1. Se hace push a `master`.
2. GitHub Actions ejecuta el workflow `.github/workflows/deploy.yml`.
3. El workflow abre una conexión SSH al VPS.
4. En el servidor se ejecuta `/srv/laravel/deploy.sh`.
5. El script:
   - actualiza el código desde GitHub,
   - levanta contenedores Docker,
   - instala dependencias PHP y Node,
   - compila assets con Vite,
   - limpia y recachea Laravel,
   - corre migraciones,
   - reactiva la aplicación.

### Secrets requeridos en GitHub Actions

Configurar en `Settings > Secrets and variables > Actions`:

- `VPS_HOST`: IP o dominio del VPS.
- `VPS_PORT`: puerto SSH del VPS, normalmente `22`.
- `VPS_USER`: usuario SSH del servidor.
- `VPS_SSH_KEY`: clave privada SSH usada por GitHub Actions para conectarse al VPS.

### Notas

- No subir valores reales de secrets al repositorio.
- El script de deploy usado por producción es `deploy.sh`.
- El servidor ejecuta Laravel dentro de Docker Compose.
- Los assets compilados en `public/build/` no se versionan en Git.
