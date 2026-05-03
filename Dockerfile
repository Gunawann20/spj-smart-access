# ===============================
# 1. Composer Dependencies
# ===============================
FROM php:8.2-cli-alpine AS composer_deps

WORKDIR /app

RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pdo_mysql \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader


# ===============================
# 2. Frontend Build (Vite)
# ===============================
FROM node:20-alpine AS frontend_builder

WORKDIR /app

COPY package*.json ./

RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./

RUN npm run build \
    # Pastikan output Vite benar-benar ada, kalau tidak ada langsung gagal build
    && [ -f public/build/manifest.json ] \
    || (echo "ERROR: Vite build failed — manifest.json not found in public/build/" && exit 1)


# ===============================
# 3. Runtime Laravel
# ===============================
FROM php:8.2-cli-alpine AS runtime

WORKDIR /var/www/html

RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pdo_mysql \
    zip

# Production mode
ENV APP_ENV=production
ENV APP_DEBUG=false

# Copy source code (tanpa vendor dan public/build — di-copy dari stage masing-masing)
COPY . .

# Copy vendor dari composer stage
COPY --from=composer_deps /app/vendor ./vendor

# Copy hasil build Vite (overwrite apapun yang di-copy dari COPY . .)
COPY --from=frontend_builder /app/public/build ./public/build

# Hapus file 'hot' — keberadaannya membuat Laravel paksa pakai Vite dev server
# Buat direktori yang dibutuhkan Laravel
# Set permission agar bisa berjalan sebagai non-root user (OpenShift/Kubernetes-friendly)
RUN rm -f public/hot \
    && mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chgrp -R 0 /var/www/html \
    && chmod -R g=u /var/www/html

# Jalankan artisan cache setelah semua file siap
# Gunakan APP_KEY dummy agar tidak error di build time (key asli dari env saat runtime)
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 8080

USER 1001

# Gunakan entrypoint script agar bisa menjalankan artisan optimize di runtime
# (saat env variable sudah tersedia dari orchestrator/docker-compose)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]