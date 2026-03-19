FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite-dev \
    nodejs \
    npm \
    curl \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ------- Dependencies stage -------
FROM base AS deps

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY package.json package-lock.json ./
RUN npm ci

# ------- Development stage -------
FROM base AS development

# Copy deps
COPY --from=deps /var/www/html/vendor ./vendor
COPY --from=deps /var/www/html/node_modules ./node_modules

# Copy app source (will be overridden by volume mount in dev)
COPY . .

RUN composer dump-autoload

# Nginx & supervisor config from docker context
COPY --from=docker nginx.conf /etc/nginx/http.d/default.conf
COPY --from=docker supervisord-dev.conf /etc/supervisor/conf.d/supervisord.conf

# Ensure storage & cache dirs are writable
RUN mkdir -p storage/framework/{cache/data,sessions,testing,views} \
    storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY --from=docker entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80 5173

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# ------- Deployable base stage -------
FROM base AS deploy

COPY --from=deps /var/www/html/vendor ./vendor
COPY --from=deps /var/www/html/node_modules ./node_modules

COPY . .

# Build frontend assets
RUN npm run build && rm -rf node_modules

RUN composer dump-autoload --optimize

# Nginx & supervisor config from docker context
COPY --from=docker nginx.conf /etc/nginx/http.d/default.conf
COPY --from=docker supervisord-prod.conf /etc/supervisor/conf.d/supervisord.conf

RUN mkdir -p storage/framework/{cache/data,sessions,testing,views} \
    storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY --from=docker entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# ------- Production stage -------
FROM deploy AS production

RUN cp .env.production .env \
    && php artisan key:generate --force

# ------- Staging stage -------
FROM deploy AS staging

RUN cp .env.staging .env \
    && php artisan key:generate --force
