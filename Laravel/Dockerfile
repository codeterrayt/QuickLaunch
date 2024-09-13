FROM bitnami/laravel:latest

RUN apt-get update -y
RUN apt-get upgrade -y

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer self-update --2

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN npm install --force

# Build the frontend assets
RUN npm run build

# CMD php artisan migrate --force

# Expose the port for the Laravel application
EXPOSE 8000
