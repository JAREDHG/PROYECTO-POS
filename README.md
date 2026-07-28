# Sistema POS

## Estado del proyecto

Sistema POS con el backend ya desarrollado y documentado. Hasta este momento se han completado los controladores de ventas e inventario, la base de datos, los seeders y la API documentada con Swagger. El frontend de la interfaz del punto de venta con Alpine.js sigue en desarrollo e integración.

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
APP_KEY=base64:vAAAmJN8ci4SXgpWSjUzjTDoyaUQT5f5u4CIfPq+GWA=
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

Los seeders crean usuarios base para pruebas:

- Administrador: `admin@pos.com` / `password123`
- Cajero: `cajero@pos.com` / `password123`

## Documentación de la API

La documentación Swagger está disponible en:

```text
http://localhost/api/documentation
```

Si estás usando GitHub Codespaces, puedes abrir la URL pública que se genere para el puerto 80 del contenedor.

## Notas adicionales

- El backend ya está listo para operar con ventas, inventario y endpoints documentados.
- El frontend del POS aún está pendiente de integración y no debe considerarse terminado.
- Si el contenedor de la app presenta problemas de permisos, vuelve a ejecutar el comando de `chown` y `chmod`.
