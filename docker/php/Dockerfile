FROM php:8.2-apache

# Install dependency OS dan PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip git curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    libicu-dev libzip-dev \
    fonts-dejavu-core \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Salin Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin konfigurasi virtual host Apache
COPY docker/php/apache.conf /etc/apache2/sites-available/000-default.conf

# Salin script init.sh (untuk install/optimasi Laravel)
COPY docker/php/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Set working directory agar lebih aman
WORKDIR /var/www/html

# Jalankan entrypoint (setup + start apache)
ENTRYPOINT ["/usr/local/bin/init.sh"]
