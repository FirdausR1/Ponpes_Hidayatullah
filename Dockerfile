FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Upload size config for large photos/documents
ENV PHP_UPLOAD_MAX_FILESIZE 64M
ENV PHP_POST_MAX_SIZE 64M
ENV PHP_MEMORY_LIMIT 256M

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Ensure storage & cache permissions
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"]
