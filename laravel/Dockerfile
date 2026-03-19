FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite-dev \
    gettext \
    curl \
    zip \
    unzip \
    git

# Install Bun
COPY --from=oven/bun:latest /usr/local/bin/bun /usr/local/bin/bun
RUN ln -s /usr/local/bin/bun /usr/local/bin/bunx

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ------- Dependencies stage -------
FROM base AS deps

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY package.json bun.lock ./
RUN bun install --frozen-lockfile

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
RUN bun run build && rm -rf node_modules

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

# CI/CD build args — override via: docker build --build-arg APP_URL_PRODUCTION=https://example.com
ARG APP_NAME_PRODUCTION APP_KEY_PRODUCTION APP_DEBUG_PRODUCTION APP_URL_PRODUCTION
ARG LOG_LEVEL_PRODUCTION
ARG DB_CONNECTION_PRODUCTION DB_HOST_PRODUCTION DB_PORT_PRODUCTION DB_DATABASE_PRODUCTION DB_USERNAME_PRODUCTION DB_PASSWORD_PRODUCTION
ARG SESSION_DOMAIN_PRODUCTION
ARG MAIL_MAILER_PRODUCTION MAIL_SCHEME_PRODUCTION MAIL_HOST_PRODUCTION MAIL_PORT_PRODUCTION MAIL_USERNAME_PRODUCTION MAIL_PASSWORD_PRODUCTION MAIL_FROM_ADDRESS_PRODUCTION

# Expand variables into .env (defaults defined in .env.production via :- syntax)
RUN envsubst < .env.production > .env \
    && php artisan key:generate --force

# ------- Staging stage -------
FROM deploy AS staging

ARG APP_NAME_STAGING APP_KEY_STAGING APP_DEBUG_STAGING APP_URL_STAGING
ARG LOG_LEVEL_STAGING
ARG DB_CONNECTION_STAGING DB_HOST_STAGING DB_PORT_STAGING DB_DATABASE_STAGING DB_USERNAME_STAGING DB_PASSWORD_STAGING
ARG SESSION_DOMAIN_STAGING
ARG MAIL_MAILER_STAGING MAIL_SCHEME_STAGING MAIL_HOST_STAGING MAIL_PORT_STAGING MAIL_USERNAME_STAGING MAIL_PASSWORD_STAGING MAIL_FROM_ADDRESS_STAGING

RUN envsubst < .env.staging > .env \
    && php artisan key:generate --force
