FROM php:8.2-apache
# Extensiones PHP: SQLite (por defecto para la app) y opcional MySQL
RUN apt-get update && apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# (Opcional, pero útil si usas .htaccess y URLs limpias)
RUN a2enmod rewrite
# Directorio de trabajo y código de la app
WORKDIR /var/www/html
COPY . /var/www/html/
EXPOSE 80
