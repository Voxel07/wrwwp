FROM php:8.3-apache

# Install dependencies required for Composer and WordPress
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    mariadb-client \
    git \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) zip mysqli pdo_mysql gd bcmath

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Suppress FQDN warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Change Apache port to 8080 for non-root execution
RUN sed -ri -e 's!80!8080!g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Setup DocumentRoot to point to the Bedrock web directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf



# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files — set ownership at copy time (avoids a separate chown traversal)
WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html/

# Run Composer Install to fetch WordPress, plugins, and dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Only chown dirs created AFTER the COPY (composer output + apache runtime)
# /var/www/html is already owned by www-data via --chown above
RUN mkdir -p /var/run/apache2 /var/log/apache2 \
 && chown -R www-data:www-data \
    /var/run/apache2 \
    /var/log/apache2 \
    /etc/apache2 \
    /var/www/html/web/wp \
    /var/www/html/vendor

# Change to non-root user
USER www-data

# Expose the new port
EXPOSE 8080
