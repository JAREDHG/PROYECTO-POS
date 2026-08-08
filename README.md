# Sistema POS – Punto de Venta

Un sistema integral de punto de venta construido sobre **Laravel 12** con arquitectura moderna, seguridad y despliegue en contenedores Docker. Diseñado para simplificar operaciones de tienda, gestión de inventario y procesamiento de transacciones con una interfaz intuitiva y reactiva.

---

## Estado del Proyecto

El sistema POS cuenta con un backend completamente funcional y documentado, además de un frontend interactivo construido con **Tailwind CSS** y **Alpine.js**. 

### Módulos Implementados

| Módulo | Descripción | Estado |
|--------|-------------|--------|
| **Dashboard** | Métricas en tiempo real y visualización de datos conectados directamente a la API | ✓ |
| **Punto de Venta (POS)** | Catálogo interactivo con categorías, carrito reactivo y procesamiento de transacciones | ✓ |
| **Control de Pagos** | Gestión de métodos de pago (Tarjeta, Transferencia) con bloqueos configurables e indicadores visuales | ✓ |
| **Inventario** | CRUD completo con buscador reactivo (nombre, SKU, categoría), sincronización en tiempo real vía API | ✓ |
| **Historial de Ventas** | Registro de transacciones con auditoría e integridad referencial | ✓ |
| **Seguridad & Roles** | Autenticación con Sanctum, autorización con Spatie (políticas: `manage products`, `process sales`) | ✓ |
| **Navegación** | Barra lateral intuitiva con cierre de sesión seguro y control de acceso por rol | ✓ |
| **Documentación API** | Endpoints documentados automáticamente con Swagger/OpenAPI | ✓ |
| **Reportes Avanzados** | Módulo analítico institucional con filtros dinámicos por rango de fechas, exportación a Excel y maquetación de impresión/PDF  | ✓ |

---

## Arquitectura Técnica

El proyecto utiliza una **arquitectura multi-contenedor** con Docker Compose, optimizada para colaboración en equipo y sincronización de datos en tiempo real.

### Stack Tecnológico

```
┌─────────────────────────────────────────────────────────────┐
│                  Interfaz (Blade + Alpine.js)                │
│                    Tailwind CSS + Vite                       │
└──────────────────────┬──────────────────────────────────────┘
                       │ HTTP/HTTPS
┌──────────────────────▼──────────────────────────────────────┐
│              Nginx (Proxy Inverso – Puerto 80)              │
│   Enrutamiento de Peticiones y Servicio de Activos         │
└──────────────────────┬──────────────────────────────────────┘
                       │ TCP/9000
┌──────────────────────▼──────────────────────────────────────┐
│         Laravel 12 + PHP 8.3-FPM (Alpine Linux)             │
│     Ejecución Sin Privilegios (www-data – UID 1000)         │
│  Sanctum | Spatie Permissions | L5-Swagger | Predis         │
└──────────────────────┬──────────────────────────────────────┘
           │            │            │
           │            │            │
    ┌──────▼──────┐  ┌──────▼─┐  ┌──────▼──────┐
    │ MySQL       │  │ Redis  │  │ Caché de    │
    │ Clever Cloud│  │ Alpine │  │ Sesiones   │
    └─────────────┘  └────────┘  └────────────┘
```

**Desglose de Capas:**

- **Capa de Presentación (Interfaz):** Frontend interactivo construido con Blade (motor de plantillas de Laravel), Alpine.js para reactividad sin dependencias pesadas, Tailwind CSS para estilos y Vite como bundler de assets. Comunica con el backend mediante solicitudes HTTP/HTTPS.

- **Capa de Enrutamiento (Nginx):** Servidor web ultra-ligero que actúa como proxy inverso, escucha peticiones HTTP en el puerto 80, las enruta hacia la aplicación Laravel en el puerto 9000 (PHP-FPM) y sirve activos estáticos sin sobrecargar el procesador de la aplicación.

- **Capa de Aplicación (Laravel + PHP-FPM):** Núcleo de lógica de negocio ejecutado sobre Laravel 12 con PHP 8.3-FPM en contenedor Alpine Linux. Implementa autenticación de API mediante Laravel Sanctum, gestión de permisos granulares con Spatie, documentación automática con L5-Swagger y cliente Redis (Predis) para caché distribuido. Cumple con RNF03: se ejecuta sin privilegios root bajo el usuario `www-data` (UID 1000).

- **Capa de Persistencia y Caché:** Combina almacenamiento de datos en MySQL Clever Cloud (base de datos remota centralizada que sincroniza el estado en tiempo real entre colaboradores) con Redis Alpine (sesiones, rate limiting y datos de alta frecuencia).

### Servicios Docker Compose

| Servicio | Imagen | Propósito | Puertos |
|----------|--------|-----------|---------|
| **db** | `mariadb:10.11-jammy` | Base de datos relacional compartida (Clever Cloud compatible) | 3306 |
| **redis** | `redis:alpine` | Caché, rate limiting y sesiones | 6379 |
| **app** | `php:8.3-fpm-alpine` (build local) | Aplicación Laravel con PHP-FPM bajo usuario `www-data` | 9000 |
| **nginx** | `nginx:alpine` | Reverse proxy y servidor de activos estáticos | 80 |

### Principios de Seguridad Implementados

- **Non-root execution:** PHP-FPM se ejecuta bajo el usuario `www-data` sin acceso root
- **Permisos granulares:** Directorio `storage/` y `bootstrap/cache/` con permisos explícitos (775)
- **Multi-stage build:** Dockerfile optimizado usando imagen ultra-ligera Alpine Linux
- **Gestión de dependencias:** Composer ejecutado en etapa separada para reducir tamaño de imagen final

---

## Instalación Rápida

### Requisitos Previos

- **Docker Desktop** o **Docker Engine** (v20.10+)
- **Docker Compose** (v1.29+)
- Terminal bash/zsh (Git Bash en Windows)
- GitHub Codespaces o entorno Linux local

### Pasos de Instalación

#### 1. Clonar el Repositorio

```bash
git clone https://github.com/JAREDHG/PROYECTO-POS.git
cd PROYECTO-POS
```

#### 2. Levantar los Servicios

Ejecuta Docker Compose en modo *detached*:

```bash
docker compose up -d db redis app nginx
```

> **Nota:** Espera ~15 segundos para que MariaDB inicie su healthcheck y Redis esté listo.

#### 3. Asignar Permisos de Almacenamiento

Configura propiedad y permisos en directorios críticos (ejecutar como root en el contenedor):

```bash
docker compose exec --user root app sh -c "\
  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
  chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"
```

#### 4. Instalar Dependencias de Composer

```bash
docker compose exec app composer install
```

#### 5. Generar App Key de Laravel

```bash
docker compose exec app php artisan key:generate
```

#### 6. Ejecutar Migraciones de Base de Datos

```bash
docker compose exec app php artisan migrate
```

#### 7. Cargar Seeders de Prueba

Crea datos iniciales (usuarios, roles, productos de ejemplo):

```bash
docker compose exec app php artisan db:seed
```

#### 8. Generar Documentación Swagger

Regenera la documentación interactiva de la API:

```bash
docker compose exec app php artisan l5-swagger:generate
```

---

## Configuración de Entorno

Copia `.env.example` a `.env` y actualiza según tu ambiente:

```bash
cp .env.example .env
```

### Variables Clave para Docker

```env
# =========== Aplicación ===========
APP_NAME="Abarrotes El Surtidor"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_KEY=base64:YOUR_KEY_HERE

# =========== Base de Datos ===========
DB_CONNECTION=mysql
DB_HOST=db              # Nombre del servicio Docker
DB_PORT=3306
DB_DATABASE=pos_db
DB_USERNAME=pos_user
DB_PASSWORD=pos_password

# =========== Cache & Sessions ===========
CACHE_STORE=database
SESSION_DRIVER=database
REDIS_HOST=redis        # Nombre del servicio Docker
REDIS_PORT=6379

# =========== Mail ===========
MAIL_MAILER=log
MAIL_FROM_ADDRESS="sistema@pos.com"
```

> **Para producción en Clever Cloud:** Actualiza `DB_HOST` con la URL de tu base de datos remota y ajusta credenciales según tu proveedor.

### Sincronización Multi-Equipo

El proyecto está configurado para usar una **base de datos MySQL remota centralizada** (Clever Cloud). Todos los miembros del equipo pueden conectarse a la misma instancia:

1. Obtén las credenciales del administrador
2. Actualiza `DB_HOST`, `DB_USERNAME` y `DB_PASSWORD` en `.env`
3. Ejecuta migraciones una sola vez (coordinado en equipo)
4. Los cambios se sincronizarán automáticamente en tiempo real

---

## Credenciales de Prueba

Los seeders crean usuarios base listos para testing:

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| Admin | `admin@pos.com` | `password123` | Administrador |
| Cajero | `cajero@pos.com` | `password123` | Cajero |

> **Cambiar credenciales antes de producción.**

---

## API & Documentación

### Endpoints Principales

| Método | Endpoint | Descripción | Auth | Permisos |
|--------|----------|-------------|------|----------|
| **POST** | `/api/login` | Autenticación de usuario | — | — |
| **POST** | `/api/logout` | Cierre de sesión | Sanctum | — |
| **GET** | `/api/user` | Datos del usuario autenticado | Sanctum | — |
| **GET** | `/api/products` | Listar productos activos | Sanctum | — |
| **POST** | `/api/products` | Crear producto | Sanctum | `manage products` |
| **PUT** | `/api/products/{id}` | Actualizar producto | Sanctum | `manage products` |
| **DELETE** | `/api/products/{id}` | Eliminar producto (soft delete) | Sanctum | `manage products` |
| **GET** | `/api/products/inactive` | Papelera de productos | Sanctum | `manage products` |
| **PUT** | `/api/products/{id}/restore` | Restaurar producto | Sanctum | `manage products` |
| **POST** | `/api/sales` | Crear venta/transacción | Sanctum | `process sales` |
| **GET** | `/api/sales` | Historial de ventas | Sanctum | — |

### Documentación Interactiva (Swagger)

Accede a la documentación interactiva en:

```
http://localhost/api/documentation
```

O en GitHub Codespaces:

```
https://<CODESPACE-URL>/api/documentation
```

Explora todos los endpoints, prueba solicitudes y descarga especificaciones OpenAPI.

---

## Estructura del Proyecto

```
PROYECTO-POS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Autenticación (login/logout)
│   │   │   ├── ProductController.php    # CRUD de productos
│   │   │   └── SaleController.php       # Procesamiento de ventas
│   │   └── Middleware/                  # Middlewares personalizados
│   ├── Models/
│   │   ├── User.php                     # Modelo de usuario
│   │   ├── Product.php                  # Modelo de producto
│   │   ├── Sale.php                     # Modelo de venta
│   │   └── SaleItem.php                 # Modelo de ítems de venta
│   └── Providers/
│       └── AppServiceProvider.php       # Proveedores de servicios
├── bootstrap/                            # Archivos de inicialización
├── config/                               # Configuración de la aplicación
│   ├── auth.php                          # Configuración de autenticación
│   ├── database.php                      # Configuración de base de datos
│   ├── permission.php                    # Spatie Permissions
│   └── l5-swagger.php                    # Swagger/OpenAPI
├── database/
│   ├── migrations/                       # Migraciones de esquema
│   ├── seeders/                          # Seeders de datos iniciales
│   └── factories/                        # Factories para testing
├── resources/
│   ├── css/app.css                       # Estilos Tailwind CSS
│   ├── js/app.js                         # JavaScript principal (Alpine.js)
│   └── views/                            # Vistas Blade
│       ├── dashboard.blade.php           # Panel de control
│       ├── pos.blade.php                 # Interfaz POS
│       ├── inventario.blade.php          # Gestión de inventario
│       └── login.blade.php               # Autenticación
├── routes/
│   ├── api.php                           # Rutas API REST
│   ├── web.php                           # Rutas web/vistas
│   └── console.php                       # Comandos de consola
├── storage/                              # Logs, uploads, cache (usuario www-data)
├── nginx/
│   └── default.conf                      # Configuración de Nginx
├── Dockerfile                            # Multi-stage build (Alpine)
├── docker-compose.yml                    # Orquestación de servicios
├── .env.example                          # Plantilla de variables
├── composer.json                         # Dependencias PHP
└── vite.config.js                        # Configuración de Vite

```

---

## Desarrollo y Comandos Útiles

### Ejecutar Artisan en el Contenedor

```bash
# Crear migración
docker compose exec app php artisan make:migration create_tablename_table

# Crear modelo con controlador y migración
docker compose exec app php artisan make:model Product -mcr

# Limpiar caché
docker compose exec app php artisan cache:clear

# Ver rutas registradas
docker compose exec app php artisan route:list
```

### Ver Logs de la Aplicación

```bash
# Seguimiento en tiempo real
docker compose logs -f app

# Solo últimas 100 líneas
docker compose logs app --tail=100
```

### Acceso a la Consola

```bash
# Tinker (REPL interactiva de Laravel)
docker compose exec app php artisan tinker
```

### Detener Servicios

```bash
# Pausar todos los servicios
docker compose stop

# Eliminar contenedores (datos persistentes en volumen db_data)
docker compose down
```

---

## Solución de Problemas Comunes

### Problema: "Connection refused" en base de datos

**Causa:** MariaDB no ha completado su inicialización.

**Solución:**
```bash
docker compose logs db                  # Ver logs de MariaDB
docker compose ps                        # Verificar que db esté "healthy"
sleep 15 && docker compose exec app php artisan migrate
```

### Problema: Permisos denegados en `storage/` o `bootstrap/cache/`

**Causa:** Propietario de archivos incorrecto.

**Solución:**
```bash
docker compose exec --user root app sh -c "\
  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
  chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"
```

### Problema: Port 80 ya está en uso

**Causa:** Otro servicio está escuchando en el puerto 80.

**Solución:** Modifica `docker-compose.yml`:
```yaml
nginx:
  ports:
    - "8080:80"   # Cambia a puerto 8080
```

Luego accede a `http://localhost:8080`

---

## Despliegue en Producción

### Checklist Predeployment

- [ ] Cambiar `APP_ENV` a `production`
- [ ] Establecer `APP_DEBUG=false`
- [ ] Generar `APP_KEY` único con `key:generate`
- [ ] Configurar base de datos remota (Clever Cloud, AWS RDS, etc.)
- [ ] Configurar Redis en producción (si se requiere caché distribuido)
- [ ] Revisar y actualizar permisos en `storage/` y `bootstrap/cache/`
- [ ] Certificado SSL/TLS para Nginx (Let's Encrypt + Certbot)
- [ ] Configurar backups automáticos de base de datos
- [ ] Establecer límites de rate limiting en API
- [ ] Revisar políticas de CORS según ambiente

### Construcción de Imagen para Producción

```bash
# Build sin dependencias de desarrollo
docker build -t pos-app:latest --build-arg COMPOSER_FLAGS="--no-dev --optimize-autoloader" .

# Push a registro (Docker Hub, ECR, etc.)
docker tag pos-app:latest registro.com/pos-app:latest
docker push registro.com/pos-app:latest
```

---

## Dependencias Principales

| Paquete | Versión | Propósito |
|---------|---------|-----------|
| **laravel/framework** | ^12.0 | Framework base |
| **laravel/sanctum** | ^4.3 | Autenticación API (tokens) |
| **spatie/laravel-permission** | ^8.3 | Gestión de roles y permisos |
| **darkaonline/l5-swagger** | ^11.1 | Documentación Swagger/OpenAPI |
| **predis/predis** | ^3.5 | Cliente Redis para caché |
| **laravel/tinker** | ^2.10 | REPL interactiva |

---

## Contribución y Buenas Prácticas

### Flujo de Desarrollo

1. Crear rama feature: `git checkout -b feature/nueva-funcionalidad`
2. Realizar cambios y testing local
3. Commit con mensajes descriptivos: `git commit -m "feat: agregar módulo X"`
4. Push y abrir Pull Request
5. Code review y merge a `main`

### Estándares de Código

- **PSR-12** para estilos PHP
- **Laravel style guide** para convenciones
- Ejecutar `composer pint` antes de commit

```bash
docker compose exec app composer pint
```

---

## Soporte y Contacto

Para preguntas, issues o sugerencias:

- **Issues:** [GitHub Issues](https://github.com/JAREDHG/PROYECTO-POS/issues)
- **Email:** soporte@pos.local

---

## Licencia

Este proyecto está licenciado bajo la **MIT License** – ver archivo [LICENSE](LICENSE) para detalles.
