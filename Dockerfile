# ==========================================
# ETAPA 1: Construcción y dependencias PHP (Composer)
# ==========================================
FROM composer:2.7 AS builder
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# ==========================================
# ETAPA 2: Entorno de producción en Alpine Linux (Ultra-ligero)
# ==========================================
FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    bash \
    && docker-php-ext-install pdo pdo_mysql

# Copiar artefactos limpios de desarrollo
COPY --from=builder /app /var/www/html

# RNF03: Configurar usuario sin privilegios root (www-data / uid 1000)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
USER www-data

EXPOSE 9000
CMD ["php-fpm"]