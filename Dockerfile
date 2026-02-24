# Stage 1 - Build Frontend
FROM node:18 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2 - Backend
FROM php:8.2-fpm AS backend

# PostgreSQL અને અન્ય જરૂરી પેકેજ ઇન્સ્ટોલ કરો
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .
COPY --from=frontend /app/public ./public 

RUN composer install --no-dev --optimize-autoloader

# પરમિશન સેટ કરો (Hostinger VPS માટે જરૂરી)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

CMD ["php-fpm"]
