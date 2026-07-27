# Sistema POS

## Estado del proyecto

Sistema POS con el backend ya desarrollado y documentado. Actualmente se han completado los controladores de ventas e inventario, la base de datos, los seeders y la API documentada con Swagger. El frontend de la interfaz del punto de venta con Alpine.js sigue en desarrollo e integración.

## Arquitectura

El proyecto está preparado para ejecutarse con Docker Compose, utilizando los siguientes servicios:

- `db`: contenedor de MariaDB 10.11 para la base de datos relacional.
- `redis`: contenedor de Redis para caché y limitación de peticiones.
- `app`: contenedor de la aplicación Laravel con PHP-FPM.
- `nginx`: proxy inverso para servir la aplicación.

La configuración de entorno está alineada con estos nombres de servicio para que la aplicación pueda conectarse correctamente dentro de la red Docker.

## Instalación y configuración inicial

### 1. Levantar los contenedores

En GitHub Codespaces, ejecuta lo siguiente desde la raíz del proyecto:

```bash
cd /workspaces/PROYECTO-POS
docker compose up -d db redis app nginx
```

> Nota: espera aproximadamente 10 segundos después de levantar los contenedores antes de correr las migraciones, para que MariaDB y Redis queden listos.

### 2. Ajustar permisos de almacenamiento

```bash
docker compose exec --user root app sh -c "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"
```

### 3. Instalar dependencias de Composer

```bash
docker compose exec app composer install
```

### 4. Generar la App Key de Laravel

```bash
docker compose exec app php artisan key:generate
```

### 5. Ejecutar migraciones

```bash
docker compose exec app php artisan migrate
```

### 6. Cargar los seeders de prueba

```bash
docker compose exec app php artisan db:seed
```

### 7. Regenerar la documentación de Swagger

```bash
docker compose exec app php artisan l5-swagger:generate
```

### 8. Variables de entorno recomendadas

El proyecto ya está configurado para trabajar con Docker usando estas variables en el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=pos_db
DB_USERNAME=pos_user
DB_PASSWORD=pos_password

REDIS_HOST=redis
REDIS_PORT=6379
```

## Documentación de la API

La documentación Swagger está disponible en:

```text
http://localhost/api/documentation
```

Si estás usando un entorno remoto como GitHub Codespaces, puedes abrir la URL pública que se genere para el puerto 80 del contenedor.
