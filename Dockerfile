# SistTa - CodeIgniter 4 sobre PHP 8.4 + Apache
FROM php:8.4-apache

# Extensiones que exige CodeIgniter 4 (intl, mbstring) y el driver MySQLi
RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev \
    && docker-php-ext-install intl mysqli \
    && rm -rf /var/lib/apt/lists/*

# Configuración PHP de producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && { \
        echo 'expose_php = Off'; \
        echo 'upload_max_filesize = 8M'; \
        echo 'post_max_size = 10M'; \
        echo 'date.timezone = America/Lima'; \
        echo 'session.cookie_httponly = 1'; \
        echo 'opcache.enable = 1'; \
        echo 'opcache.validate_timestamps = 0'; \
    } > "$PHP_INI_DIR/conf.d/sistta.ini"

# Apache: DocumentRoot en public/ y mod_rewrite para el .htaccess de CI4
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && a2enmod rewrite headers \
    && { echo 'ServerTokens Prod'; echo 'ServerSignature Off'; } > /etc/apache2/conf-enabled/zz-sistta.conf

WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html

COPY docker/entrypoint.sh /usr/local/bin/sistta-entrypoint
RUN chmod +x /usr/local/bin/sistta-entrypoint

EXPOSE 80
ENTRYPOINT ["sistta-entrypoint"]
CMD ["apache2-foreground"]
