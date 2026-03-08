FROM php:8.2-apache

# Extensiones PHP necesarias para MySQL/MariaDB
RUN docker-php-ext-install pdo pdo_mysql

# (Opcional, pero útil si usas .htaccess y URLs limpias)
RUN a2enmod rewrite

# Directorio de trabajo y código de la app
WORKDIR /var/www/html
COPY . /var/www/html/

EXPOSE 80