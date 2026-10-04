# Mariana Robles — demo inmobiliaria

MVP residencial para una asesora independiente en Querétaro. Laravel 13, Livewire 4, Tailwind CSS 4 y esquema compatible con MySQL. Favoritos locales y mapa ilustrativo. Sin LLM, CRM ni autenticación.

## Railway

La configuración de producción está incluida. Sigue [los pasos de despliegue](RAILWAY.md) y utiliza `.env.railway.example` para las variables del servicio.

## Ver la demo local

Las dependencias y assets están instalados. El entorno local usa SQLite porque no había MySQL activo.

```sh
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
```

Abre http://127.0.0.1:8000. Para editar estilos: `npm run dev`; para compilarlos: `npm run build`.

## MySQL

`.env.example` está preparado para MySQL. Crea una base dedicada `bienes_raices` y un usuario con permisos sobre ella. En `.env`, define `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`. Ejecuta `php artisan config:clear` y `php artisan migrate --seed`. Las migraciones, el seeder y las páginas públicas se validaron con MySQL 8.4 en la imagen de producción.

## Contacto

Configura `WHATSAPP_NUMBER` con código de país y número, por ejemplo el formato internacional de tu número mexicano, sin el signo +. Si está vacío, los CTA muestran Próximamente y no envían a un contacto inventado. `ADVISOR_NAME` y `ADVISOR_EMAIL` también se configuran en `.env`. Luego ejecuta `php artisan config:clear`.

## Estructura

- `Property`: propiedades, imágenes y amenidades como JSON flexible.
- `PropertySearch`: consulta pública y filtros combinables.
- `PropertyCatalog`: filtros reactivos, estado en URL, paginación y estado vacío.
- `AdvisorDemo`: conversación de muestra con tres opciones predefinidas. No interpreta lenguaje natural.
- `WhatsApp`: generación centralizada de mensajes y URLs contextuales.
- Blade: layout, cards, landing, catálogo y detalle con galería ampliable y contacto fijo.
- Factory y seeder idempotente: diez propiedades ficticias con copy propio.

Las imágenes son ilustrativas de Unsplash y no representan inventario real. Nombre, retrato, ubicaciones y precios son demostrativos. Los datos sobre escuelas son descriptivos, sin verificación geográfica. La galería abre las fotos a tamaño completo. El seeder conserva las propiedades no incluidas y actualiza las que coincidan por slug.

## Validación

```sh
php artisan test
npm run build
```

Los tests cubren rutas públicas, protección de propiedades no publicadas, filtros Livewire combinados, selección de ejemplo y mensajes WhatsApp. Usan una base SQLite en memoria.

## Evolución

Una futura administración puede reutilizar Property; la búsqueda conversacional puede sustituir la selección dentro de AdvisorDemo y consultar PropertySearch. Autenticación, asesores, leads, CRM, MCP y WhatsApp API están fuera de esta demo. Antes de publicar se deben sustituir los datos demo, configurar contacto, confirmar inventario y desactivar APP_DEBUG.
