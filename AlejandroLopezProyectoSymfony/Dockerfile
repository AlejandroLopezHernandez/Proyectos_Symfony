FROM php:8.2-cli


# Instalar dependencias y Composer

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install mysqli pdo_mysql 

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libmariadb-dev \
    && docker-php-ext-install pdo pdo_mysql
# Instalar Composer y Symfony CLI
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony
# Instalar dependencias de Composer (incluyendo MakerBundle)
#RUN composer require symfony/maker-bundle --dev