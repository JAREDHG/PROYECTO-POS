# Sistema POS (Punto de Venta)

## Estado del proyecto

Sistema POS integral con el backend desarrollado, documentado y un frontend interactivo construido con **Tailwind CSS** y **Alpine.js**. 

### ✅ Módulos y Funcionalidades Completadas:
* [x] **Dashboard:** Métricas y datos reales conectados directamente con la API.
* [x] **Punto de Venta (POS):** Catálogo interactivo de productos con categorías funcionales y carrito de compras reactivo.
* [x] **Control de Pagos (POS):** Bloqueo interactivo temporal de Tarjeta y Transferencia con aviso de mantenimiento y estilo visual en alerta.
* [x] **Barra Lateral:** Navegación optimizada con cierre de sesión seguro y funcional.
* [x] **Historial de Ventas:** Módulo de transacciones concluido e intacto.
* [x] **Inventario (CRUD Completo):** Listado dinámico, buscador reactivo (por nombre, SKU o categoría), registro, edición y eliminación conectados en tiempo real con la base de datos vía API REST (`/api/products`).
* [x] **Seguridad y Roles:** Protección de rutas de API mediante Laravel Sanctum y políticas de autorización (`can:manage products`, `can:process sales`).
* [x] **Documentación:** Endpoints documentados con Swagger / OpenAPI.

### 📋 Pendientes por Desarrollar:
* [ ] **Reportes:** Revisión de módulos analíticos, buscador dinámico y función de exportación/descarga.
* [ ] **Usuarios / Roles:** Vinculación visual del usuario autenticado, bloqueo de vistas de administrador para cajeros, y módulo de registro/modificación de personal.
* [ ] **Módulo de Cajero:** Pruebas integrales de flujo transaccional y restricciones de caja.
* [x] **Base de Datos:** Ajuste de políticas de integridad referencial (borrado en cascada o baja lógica) para evitar bloqueos por llaves foráneas al eliminar productos con historial de ventas.

## Arquitectura

El proyecto está preparado para ejecutarse con Docker Compose usando los siguientes servicios:

- `db`: contenedor de MariaDB 10.11 para la base de datos relacional.
- `redis`: contenedor de Redis para caché y limitación de peticiones.
- `app`: contenedor de la aplicación Laravel con PHP-FPM.
- `nginx`: proxy inverso para servir la aplicación.

La configuración de entorno está alineada con estos nombres de servicio para que la aplicación pueda conectarse correctamente dentro de la red Docker.

## Requisitos previos

- Docker Desktop o Docker Engine instalado.
- Docker Compose disponible en la terminal.
- GitHub Codespaces o un entorno Linux con acceso a puertos.

## Instalación y configuración inicial

### 1. Clonar el proyecto

```bash
git clone <repo-url>
cd PROYECTO-POS
```

### 2. Levantar los contenedores

En GitHub Codespaces o en un entorno local, ejecuta:

```bash
docker compose up -d db redis app nginx
```

> Nota: espera aproximadamente 10 segundos después de levantar los contenedores antes de correr las migraciones, para que MariaDB y Redis queden listos.

### 3. Ajustar permisos de almacenamiento

```bash
docker compose exec --user root app sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
```

### 4. Instalar dependencias de Composer

```bash
docker compose exec --user root app composer install
```

### 5. Generar la App Key de Laravel

```bash
docker compose exec --user root app php artisan key:generate
```

### 6. Ejecutar migraciones

```bash
docker compose exec --user root app php artisan migrate
```

### 7. Cargar los seeders de prueba

```bash
docker compose exec --user root app php artisan db:seed
```

### 8. Regenerar la documentación de Swagger

```bash
docker compose exec --user root app php artisan l5-swagger:generate
```

## Variables de entorno

El proyecto usa un archivo `.env` con la siguiente configuración base para Docker:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=pos_db
DB_USERNAME=pos_user
DB_PASSWORD=pos_password

REDIS_HOST=redis
REDIS_PORT=6379
```

## Credenciales de prueba

Los seeders crean usuarios base paraV pruebas:

- Administrador: `admin@pos.com` / `password123`
- Cajero: `cajero@pos.com` / `password123`

## Documentación de la API

La documentación Swagger está disponible en:

```text
http://localhost/api/documentation
```

Si estás usando GitHub Codespaces, puedes abrir la URL pública que se genere para el puerto 80 del contenedor.

## Notas adicionales

- El backend y el frontend administrativo de inventario y POS se encuentran integrados y funcionales.
- Si el contenedor de la app presenta problemas de permisos, vuelve a ejecutar el comando de `chown` y `chmod`.
