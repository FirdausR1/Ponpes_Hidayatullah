FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Upload size config for large photos/documents (base image auto-appends 'M')
ENV PHP_UPLOAD_MAX_FILESIZE 64
ENV PHP_POST_MAX_SIZE 64
ENV PHP_MEM_LIMIT 256

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Ensure storage & cache permissions and configure startup wrapper
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod +x /var/www/html/conf/nginx/start-nginx.sh && \
    sed -i 's|command=/usr/sbin/nginx.*|command=/var/www/html/conf/nginx/start-nginx.sh|' /etc/supervisord.conf

CMD ["/start.sh"]
