# Publicar la demo en Railway

La aplicación se construye con el Dockerfile incluido: PHP 8.4, Apache, dependencias de producción y assets compilados con Node 22. En Railway se configura `Dockerfile` como ruta de construcción, `sh railway-predeploy.sh` como comando previo y `/up` como comprobación de salud. El servicio conectado ya tiene estos ajustes. No necesitas un proceso Vite ni un worker para esta demo.

## 1. Crear los servicios

En un proyecto nuevo de Railway añade **MySQL** y un servicio para esta aplicación. Mantén ambos en el mismo proyecto y entorno para usar la conexión privada.

El repositorio de esta demo es [solrakmnk/medrano_bienesraices](https://github.com/solrakmnk/medrano_bienesraices), rama `main`. Railway se conecta a esa rama para desplegar sus actualizaciones. Nunca subas `.env`.

También puedes subir la carpeta directamente con la CLI ya instalada:

```sh
railway login
railway init
railway add --database mysql
railway add --service inmobiliaria
```

Después, desde esta carpeta:

```sh
railway link
railway service link inmobiliaria
```

Selecciona el proyecto y **el servicio de la aplicación**, no el de MySQL.

## 2. Configurar las variables

En el servicio de la aplicación abre **Variables → Raw Editor** y copia el contenido de `.env.railway.example`. Las referencias suponen que la base se llama `MySQL`; si elegiste otro nombre, cambia ese prefijo en las cinco referencias.

Genera una clave desde tu equipo:

```sh
php artisan key:generate --show
```

Copia el resultado completo en `APP_KEY` de Railway. Guarda esa clave: debe permanecer igual entre despliegues. No uses `key:generate` al iniciar el servidor ni compartas la clave en el repositorio.

Para mostrar las diez propiedades de ejemplo, establece `DEMO_SEED_ON_DEPLOY=1` en el primer despliegue. Después cámbialo a `0`: el seeder actualiza las propiedades que coinciden por slug y podría sobrescribir cambios futuros. Las migraciones conservan los datos; no se ejecuta `migrate:fresh`.

`WHATSAPP_NUMBER` queda vacío hasta que configures el contacto correcto. Completa también `ADVISOR_NAME` y `ADVISOR_EMAIL`.

## 3. Desplegar y generar dominio

Con GitHub, despliega el servicio conectado. Con la CLI, desde esta carpeta y con el servicio de aplicación seleccionado:

```sh
railway up
```

En **Settings → Networking → Generate Domain**, genera el dominio público. Si pide el puerto de destino, usa `8080`; la imagen también respeta el puerto que Railway proporcione mediante `PORT`.

Actualiza `APP_URL` con la URL HTTPS completa del dominio y vuelve a desplegar para aplicar el cambio. Mantén `APP_DEBUG=false` y `SESSION_SECURE_COOKIE=true`.

## 4. Revisar la demo

Comprueba inicio, catálogo, detalle, filtros, favoritos después de recargar y mapa. `/up` debe responder correctamente. Si falla el despliegue, consulta los logs de construcción, migración e inicio; el registro de Laravel se envía a Railway.

Los favoritos se guardan en el navegador. El mapa, servicios, rutas, propiedades y marca son demostrativos. Las fotos incluidas forman parte de la imagen y persisten al redesplegar. El almacenamiento local de nuevos archivos es temporal: antes de añadir cargas de fotos, configura almacenamiento persistente u object storage. La base MySQL conserva el inventario.

La preparación se validó construyendo la imagen Docker, ejecutando migraciones y seeder contra MySQL 8.4, y comprobando inicio, catálogo y `/up` con respuestas HTTP 200. Pasan las 13 pruebas automatizadas.

Proyecto Railway: `demo_mc`. Dominio: https://demo-mc.up.railway.app. Las variables y la clave de producción se guardan en Railway.

Referencias oficiales: [Laravel en Railway](https://docs.railway.com/guides/laravel), [configuración como código](https://docs.railway.com/reference/config-as-code), [MySQL](https://docs.railway.com/databases/mysql).
