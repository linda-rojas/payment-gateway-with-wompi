# Usar la imagen oficial de PHP con Apache
FROM php:8.3-apache

# Instalar las extensiones necesarias para PHP
RUN docker-php-ext-install pdo pdo_mysql

# Crear el archivo .env con las variables de entorno durante la construcción
RUN echo "TEST_PUBLIC_KEY=${TEST_PUBLIC_KEY}" > /var/www/html/.env && \
    echo "TEST_PRIVATE_KEY=${TEST_PRIVATE_KEY}" >> /var/www/html/.env && \
    echo "TEST_URL_EVENT=${TEST_URL_EVENT}" >> /var/www/html/.env && \
    echo "CURRENCY=${CURRENCY}" >> /var/www/html/.env && \
    echo "REFERENCE_ORDER=${REFERENCE_ORDER}" >> /var/www/html/.env && \
    echo "TEST_API_URL=${TEST_API_URL}" >> /var/www/html/.env && \
    echo "TEST_CLIENT_ID=${TEST_CLIENT_ID}" >> /var/www/html/.env && \
    echo "WOMPI_INTEGRITY_KEY=${WOMPI_INTEGRITY_KEY}" >> /var/www/html/.env && \
    echo "CUSTOMER_EMAIL=${CUSTOMER_EMAIL}" >> /var/www/html/.env

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Cambiar permisos de los archivos para que Apache pueda acceder a ellos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80 para que el contenedor sea accesible a través de HTTP
EXPOSE 80
