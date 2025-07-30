# Usar la imagen oficial de PHP con Apache
FROM php:8.3-apache

# Instalar las extensiones necesarias para PHP
RUN docker-php-ext-install pdo pdo_mysql

# Instalar gettext para usar envsubst
RUN apt-get update && apt-get install -y gettext

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Reemplazar las variables de entorno en el archivo .env usando envsubst
RUN envsubst < /var/www/html/.env.template > /var/www/html/.env

# Instalar dependencias de Composer
# Primero instalar las dependencias del sistema necesarias para Composer, como herramientas de compilación
RUN apt-get install -y libzip-dev git && \
    docker-php-ext-install zip && \
    apt-get clean

# Ejecutar composer install para instalar las dependencias PHP
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Cambiar permisos de los archivos para que Apache pueda acceder a ellos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80 para que el contenedor sea accesible a través de HTTP
EXPOSE 80
