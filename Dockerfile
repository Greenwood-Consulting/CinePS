FROM php:8.3-apache-bookworm

LABEL org.opencontainers.image.source="https://github.com/Greenwood-Consulting/CinePS"
LABEL org.opencontainers.image.description="Client PHP CinePS servi par Apache"

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        libcurl4-openssl-dev \
    && docker-php-ext-install -j"$(nproc)" curl \
    && a2enmod headers expires rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl --silent --show-error --output /dev/null http://127.0.0.1/ || exit 1

CMD ["apache2-foreground"]
