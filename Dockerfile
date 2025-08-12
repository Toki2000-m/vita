FROM php:8.2-apache

# 🧩 Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    git \
    zip \
    unzip \
    curl \
    gnupg \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# 🧩 Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🧩 Copia el código fuente
COPY . /var/www/html
WORKDIR /var/www/html

# 🧩 Instala dependencias PHP con caché
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# 🧩 Configura Apache
RUN a2enmod rewrite \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# 🧩 Instala Node.js y compila assets con Vite
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install --legacy-peer-deps \
    && npm run build

EXPOSE 80

# 🧩 Ejecuta Apache directamente
CMD /usr/sbin/apache2ctl -D FOREGROUND
