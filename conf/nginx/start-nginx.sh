#!/bin/sh
# Wait for PHP-FPM Unix socket to be ready before starting Nginx
for i in $(seq 1 30); do
    if [ -S /var/run/php-fpm.sock ]; then
        break
    fi
    sleep 0.1
done
exec /usr/sbin/nginx -g "daemon off; error_log /dev/stderr info;"
