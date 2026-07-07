#!/bin/sh
set -e

cd /var/www

if [ ! -f "vendor/autoload.php" ]; then
    echo ">>> Instalando dependencias PHP..."
    composer install --no-interaction --optimize-autoloader
fi

if [ ! -d "node_modules" ] || [ ! -f "public/build/manifest.json" ]; then
    echo ">>> Instalando dependencias Node..."
    npm install
    npm run build
fi

if [ ! -f ".env" ]; then
    echo ">>> Criando .env..."
    cp .env.example .env
fi

if ! grep -q "APP_KEY=" .env || [ "$(grep 'APP_KEY=' .env | cut -d= -f2)" = "" ]; then
    echo ">>> Gerando APP_KEY..."
    php artisan key:generate --force
fi

if [ ! -f "database/database.sqlite" ]; then
    echo ">>> Criando banco SQLite..."
    touch database/database.sqlite
    php artisan migrate --seed --force
fi

chown -R www-data:www-data /var/www/storage /var/www/database /var/www/bootstrap/cache

echo ">>> Iniciando servicos..."
service nginx start
php-fpm
