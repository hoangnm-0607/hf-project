FROM pimcore/pimcore:PHP8.0-fpm AS dev

# Needs to be writable because we change the user and files are written there within the ENTRYPOINT
RUN chmod -R 777 /usr/local/etc/php/conf.d/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /opt/phpstorm-coverage && chmod -R 777 /opt/phpstorm-coverage

# Set the user id of www-data to the id of the local user, which is passed via
# the docker-compose up command.
# If there is no user id environment variable set before, set a default
# value here to 1000, so the following commands will not crash.
ARG USER_ID=1000
RUN set -xe \
    && usermod -u $USER_ID www-data

FROM dev AS build

# Overwrite the default entrypoint with the one that bootstraps pimcore.
COPY .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY .docker/php/entrypoint-pimcore.sh /usr/local/bin/entrypoint-pimcore.sh
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/entrypoint-pimcore.sh

RUN chmod -R 665 /usr/local/etc/php/conf.d/
COPY .docker/php/php.ini /usr/local/etc/php/conf.d/99-php.ini
COPY .docker/php/redis.ini /usr/local/etc/php/conf.d/99-redis.ini
COPY .docker/php/fpm.conf /usr/local/etc/php-fpm.d/zz-fpm.conf

# Disable xdebug in production.
# @See: https://github.com/pimcore/docker/blob/master/7.4/fpm/buster/debug/entrypoint.sh#L37
RUN rm -rf /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
ENV PHP_DEBUG=0

RUN apt-get update && \
	apt-get install -y --no-install-recommends \
		openssh-client \
		gettext-base \
		nginx-light \
		rsync \
		vim \
        supervisor \
		&& \
	apt-get -y autoremove && \
	rm -rf /var/lib/apt/lists/*

# ------------
# nginx config
COPY .docker/php/nginx/nginx.conf /etc/nginx/
COPY .docker/php/nginx/htpasswd /etc/nginx/htpasswd
COPY .docker/php/nginx/vhost.conf /etc/nginx/sites-available/default
# ------------

COPY .docker/php/supervisord/ /etc/supervisor/

# Make sure the composer HOME folder is writeble by www-data. Otherwise
# composer will not be able to store cache.
RUN chown -R www-data:www-data /var/www/

# Put the application source code into this docker image
COPY --chown=www-data:www-data Source/. /var/www/pimcore/

USER www-data
WORKDIR /var/www/pimcore

# WORKAROUND: for error:
# | Unable to use the "Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor" class as the "phpdocumentor/reflection-docblock" package is not installed in .
# (as of 2021-08-09)
RUN composer require phpdocumentor/reflection-docblock --no-ansi --no-interaction --no-progress --no-scripts --ignore-platform-reqs

# redis support for caching
RUN composer require predis/predis --no-ansi --no-interaction --no-progress --no-scripts --ignore-platform-reqs

# Install php dependencies if needed.
# We don't want to run scripts here because those require access to the database, which we
# don't have while building the image. The scripts should be run as a part of the entrypoint.
RUN composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --ignore-platform-reqs

FROM build as staging
USER root

# preserve contents of Source/var under another name, so entrypoint.sh can copy them
# to the shared filesystem when the container starts.
RUN cp -pR /var/www/pimcore/var /var/www/pimcore/var.dist

#VOLUME ["/var/www/pimcore/public/", "/var/www/pimcore/frontend", "/var/www/pimcore/var/versions", "/var/www/pimcore/var/config"]

EXPOSE 8080
ENTRYPOINT ["entrypoint.sh"]
CMD ["nginx"]

