# Guía de Transmisión del Culto Dominical en Vivo

Iglesia Emancipación Cristiana Afro — Sección "Culto en Vivo" de la página web.

Esta guía está escrita para una persona **sin conocimientos técnicos**. Explica, paso a paso, cómo transmitir el culto dominical en directo por YouTube (y opcionalmente otras redes) y cómo hacer que aparezca automáticamente el banner "EN VIVO AHORA" en la web.

---

## 1. Introducción

La web muestra una sección **Culto en Vivo** (`/en-vivo`) y un **banner en la página de inicio** que se encienden solos los domingos durante el horario del culto. Para que haya algo que ver, alguien debe **emitir el video en directo** desde la iglesia usando un programa llamado **OBS Studio** y un **canal de YouTube**.

Hay dos piezas:

1. **La emisión** (lo que haces tú con la cámara y OBS). Esto sube el video a YouTube.
2. **La web** (que detecta que estás emitiendo y enciende el banner). Esto ya está programado; solo hay que configurarlo una vez.

---

## 2. Requisitos previos

- Un **computador** razonablemente moderno (idealmente con 8 GB de RAM o más).
- **Conexión a internet estable**, preferiblemente por cable. Para 720p se recomienda al menos **5 Mbps de subida**; para 1080p, **10 Mbps**.
- **OBS Studio** (gratuito): se descarga de [https://obsproject.com](https://obsproject.com).
- Un **canal de YouTube verificado** y con las transmisiones en directo habilitadas. La verificación puede tardar hasta 24 horas, así que hazlo con antelación.
- Una o dos **cámaras** (webcam, cámara de video con captura, o celular con app de cámara virtual).

---

## 3. Obtener la clave de transmisión (stream key) de YouTube Live

La "stream key" (clave de transmisión) es como una contraseña que le dice a YouTube "este video que llega es el de mi canal".

1. Entra a **YouTube Studio** → [https://studio.youtube.com](https://studio.youtube.com).
2. Arriba a la derecha pulsa **Crear** → **Transmitir en directo**.
3. Elige **Transmitir** (opción con software/OBS), no "Webcam".
4. YouTube te mostrará dos datos:
   - **URL del servidor (RTMP):** normalmente `rtmp://a.rtmp.youtube.com/live2`.
   - **Clave de transmisión:** una cadena larga de letras y números.
5. **No compartas la clave de transmisión con nadie.** Si se filtra, cualquiera podría emitir en tu canal.

> Consejo: puedes usar una clave de transmisión **permanente** para no copiarla cada domingo.

---

## 4. Configurar OBS Studio

1. Instala OBS Studio desde [https://obsproject.com](https://obsproject.com) y ábrelo.
2. Ve a **Ajustes** (Settings) → **Emisión** (Stream).
3. En **Servicio** elige **YouTube - RTMPS** (o "YouTube").
4. Pega la **clave de transmisión** que copiaste en el paso anterior.
5. Pulsa **Aplicar** y **Aceptar**.
6. Para probar la conexión, pulsa **Iniciar transmisión** unos segundos y verifica en YouTube Studio que aparece la señal; luego detén.

---

## 5. Dos cámaras en OBS

El objetivo es alternar entre una cámara general (toda la congregación/escenario) y una cámara del púlpito.

1. En OBS, abajo tienes el panel **Escenas** y, al lado, **Fuentes**.
2. Crea **dos escenas**: por ejemplo "Cámara General" y "Cámara Púlpito".
3. En cada escena, en **Fuentes**, pulsa **+** → **Dispositivo de captura de video** y selecciona la cámara correspondiente.
4. Para **cambiar de cámara en directo**, basta con hacer clic en la escena deseada. El cambio se verá en la emisión.
5. Encuadra bien: evita contraluces (ventanas detrás del pastor) y mantén la cámara estable (trípode).

> Si solo tienes una cámara, usa una sola escena. La segunda cámara se puede añadir más adelante.

---

## 6. Audio

El audio es lo más importante en un culto. Lo ideal es tomar el sonido **directamente de la consola/mezcladora** de la iglesia, no del micrófono de la cámara.

1. En **Fuentes** añade **Captura de entrada de audio** y elige la entrada conectada a la mesa de sonido (o el micrófono USB).
2. En el **Mezclador de audio** (parte inferior de OBS) vigila que la barra se mueva en **verde/amarillo**, nunca en rojo (eso es saturación).
3. Activa el **monitoreo** con auriculares para confirmar que se escucha limpio.

---

## 7. Escena básica recomendada

Orden de capas (de arriba hacia abajo en "Fuentes"):

1. **Logo de la iglesia** (imagen PNG, esquina superior).
2. **Título** "Culto Dominical" (fuente de texto).
3. **Cámara** (dispositivo de captura de video), al fondo.

Así el logo y el título quedan siempre visibles sobre la imagen de la cámara.

---

## 8. Ajustes recomendados de OBS

En **Ajustes → Salida** (modo Avanzado) y **Ajustes → Video**:

- **Resolución de salida:** 1280x720 (720p) para conexiones normales; 1920x1080 (1080p) si tu subida es buena.
- **FPS:** 30.
- **Codificador:** usa el de hardware si está disponible (NVENC, QuickSync o AMD); si no, x264.
- **Bitrate de video:** 2500–4000 kbps para 720p; 4500–6000 kbps para 1080p.
- **Bitrate de audio:** 128 kbps.

Si el video se "entrecorta", baja la resolución o el bitrate.

---

## 9. Flujo del domingo (paso a paso)

1. **Antes de empezar** (15–20 min antes): enciende el computador, abre OBS, revisa cámara y audio.
2. En YouTube Studio pulsa **Transmitir en directo** y deja la transmisión lista.
3. En OBS pulsa **Iniciar transmisión**. Verifica en YouTube que la imagen llega bien.
4. **Enciende el directo en YouTube** (botón "Transmitir en vivo" dentro de YouTube Studio).
5. **La web se encenderá sola:** si está en modo automático y es la ventana horaria del domingo, el banner "EN VIVO AHORA" aparecerá en la página de inicio y la página `/en-vivo` mostrará el reproductor. Puedes abrir la web para confirmar.
   - Si por algún motivo el banner no aparece y quieres mostrarlo ya, entra al **panel de administración → Culto en Vivo** y pulsa **"Estamos EN VIVO ahora"**.
6. **Al terminar el culto:** detén el directo en YouTube y en OBS pulsa **Detener transmisión**.
   - Si habías usado el botón de override, entra al panel y pulsa **"Finalizar transmisión"** para volver al modo automático.
7. **Archivar la grabación** (opcional): en el panel → Culto en Vivo, pega la **URL de la grabación** (el video que queda en YouTube) y pulsa **"Archivar como culto"**. Esto crea automáticamente la entrada del culto dominical de ese domingo en la web.

---

## 10. Obtener la YouTube Data API KEY y el ID de canal

Para que la web **detecte automáticamente** que estás emitiendo (y encienda el banner sola), necesita una "llave" de solo lectura hacia YouTube. Esto se configura **una sola vez**.

> Nota: esto es **opcional**. Sin la API key, la web igual funciona por **horario** (la ventana del domingo). La API key solo hace la detección más precisa.

### 10.1 Crear el proyecto y la clave

1. Entra a **Google Cloud Console** → [https://console.cloud.google.com](https://console.cloud.google.com) con la cuenta de Google de la iglesia.
2. Arriba, crea un **proyecto nuevo** (por ejemplo "ECCA Transmisión").
3. En el buscador escribe **"YouTube Data API v3"** y ábrela; pulsa **Habilitar**.
4. Ve a **APIs y servicios → Credenciales** → **Crear credenciales** → **Clave de API**.
5. Copia la clave generada (empieza con `AIza...`). Esta es tu **YOUTUBE_API_KEY**.
6. (Recomendado) Pulsa **Restringir clave** y limítala a la **YouTube Data API v3**.

### 10.2 Obtener el ID del canal

1. Entra a tu canal en YouTube.
2. Ve a **Configuración → Configuración avanzada**; ahí aparece el **ID del canal** (empieza con `UC...`). Este es tu **YOUTUBE_CHANNEL_ID**.

### 10.3 Dónde ponerlas

Estas dos claves se escriben en el archivo `.env` **del servidor** (no en el repositorio, por seguridad):

```
YOUTUBE_API_KEY=AIza...tu_clave...
YOUTUBE_CHANNEL_ID=UC...tu_canal...
```

El `YOUTUBE_CHANNEL_ID` también puede configurarse desde el panel (campo "ID de canal de YouTube"), que tiene prioridad sobre el del `.env`.

> **Importante:** la clave de API nunca se muestra en la página web ni se envía al navegador. Vive solo en el servidor.

---

## 11. Transmitir por Facebook u otra red

La detección automática **solo funciona con YouTube**. Facebook, TikTok, X e Instagram no ofrecen una forma fiable de saber "¿estoy emitiendo?", así que para esas redes se usa el **botón de override manual**.

1. Inicia tu directo en la red que quieras (Facebook Live, etc.).
2. Entra al panel → **Culto en Vivo** y pulsa **"Estamos EN VIVO ahora"**. Esto enciende el banner de la web inmediatamente, sin depender de YouTube.
3. En el formulario de configuración, pega las **URL de cada red** (Facebook, TikTok, X, Instagram). En la página `/en-vivo` aparecerán como botones "Ver también en…".
4. Al terminar, pulsa **"Finalizar transmisión"**.

> Nota sobre Facebook: el reproductor embebido de Facebook puede **no reproducir el directo** dentro de la web (Facebook exige configuración de dominio/SDK que no está incluida). Lo garantizado es el **enlace externo "Ver también en Facebook"**. Si quieres video incrustado fiable, usa YouTube como plataforma principal.

---

## 12. Solución de problemas

- **El banner no aparece en la web:**
  - Revisa que el **modo** en el panel esté en "Automático" y que sea la **ventana horaria** correcta (día y horas). El valor por defecto es domingo de 09:00 a 13:00.
  - Si es domingo dentro del horario y no aparece, pulsa **"Estamos EN VIVO ahora"** como solución inmediata.
  - Si configuraste la API key, verifica que el **ID de canal** sea correcto.
- **El video no carga en `/en-vivo`:**
  - Confirma que la URL de YouTube del panel es la del directo del canal.
  - Prueba abriendo el enlace "Ver también en YouTube".
- **El video se entrecorta / se ve pixelado:** baja la resolución o el bitrate en OBS (sección 8).
- **No se escucha:** revisa la fuente de audio en OBS (sección 6) y que la mesa de sonido esté encendida.
- **YouTube no deja transmitir:** el canal debe estar **verificado** y con transmisiones en directo habilitadas (puede tardar hasta 24 h en activarse la primera vez).

---

Ante cualquier duda técnica, contacta al responsable de sistemas de la iglesia.
