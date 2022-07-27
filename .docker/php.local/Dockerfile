FROM pimcore/pimcore:PHP8.0-fpm-debug

ARG DISABLE_XDEBUG=0
ARG USER_ID=1000

ENV COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update &&  \
    apt-get install -y --no-install-recommends \
    openssh-client \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install Dockerize to check that elasticsearch container state
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /opt/phpstorm-coverage && chmod -R 777 /opt/phpstorm-coverage

# Needs to be writable because we change the user and files are written there within the ENTRYPOINT
RUN chmod -R 777 /usr/local/etc/php/conf.d/
COPY ./php.ini /usr/local/etc/php/conf.d/99-php.ini

# Enable XDebug conditionally because it might cause performance degradation
RUN if [ "$DISABLE_XDEBUG" -eq "1" ] ; then rm -rf /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; fi
RUN if [ "$DISABLE_XDEBUG" -eq "1" ] ; then export PHP_DEBUG=0; fi

# Set the user id of www-data to the id of the local user, which is passed via
# the docker-compose up command.
# If there is no user id environment variable set before, set a default
# value here to 1000, so the following commands will not crash.
RUN set -xe && usermod -u $USER_ID www-data

USER www-data
EXPOSE 9000
