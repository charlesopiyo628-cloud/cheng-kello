FROM php:8.3-fpm 
RUN apt-get update && apt-get install -y libpq-dev nginx && docker-php-ext-install pdo_pgsql 
COPY . /var/www/html 
WORKDIR /var/www/html 
CMD [\"sh\", \"-c\", \"composer install --no-dev ^&^& php artisan migrate --force ^&^& php-fpm\"] 
