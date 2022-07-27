#!/bin/sh
set -e

# The purpose of this entrypoint is to make sure that pimcore is all set up before the
# fpm process takes control and starts processing incoming requests.
# Here we want to optimize the autoload, rebuild generated classes, run migrations, clear cache, etc.
if test $@ = "nginx"; then
    if test -d /var/www/pimcore/var/log; then
        chown www-data:www-data /var/www/pimcore/var/log
    fi

    touch /var/log/nginx/fpm-access.log /var/log/nginx/fpm-error.log
    chmod 644 /var/log/nginx/fpm-access.log /var/log/nginx/fpm-error.log

    su www-data -s /bin/bash -c /usr/local/bin/entrypoint-pimcore.sh

    php-fpm --daemonize
fi

exec "$@"

