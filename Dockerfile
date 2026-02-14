FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    zip \
    unzip \
    git \
    curl \
    libsqlite3-dev \
    libonig-dev \
    && docker-php-ext-install \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www

RUN chown -R www-data:www-data /var/www

# Install dependencies and build assets
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www

COPY nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD service nginx start && php-fpm
