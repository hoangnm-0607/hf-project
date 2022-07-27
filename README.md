# Kursmanager

Das Kursmanager-Projekt für Hansefit

Prerequisites
-------------
To build project and start development on host machine must be installed Linux (recommended)
or MacOS and some required software:
latest version of [Docker](https://docs.docker.com/install/) (version 18.06.0+)
and [docker-compose](https://docs.docker.com/compose/compose-file/),
`Git`, `Make`.

**NOTE (Linux only)**: To use Docker without sudo, please read and apply this [steps](https://docs.docker.com/engine/install/linux-postinstall/)

Installation
=============

Local/Development environment
-----------------------------

You should run command below to build and run application first time:

    make install

This command need to initialize or reinitialize local database with database dump.
Next, there is `build` command to rebuild existing project after sources update:

    make build

Next you can check status of services:

    docker-compose ps

By default, the app will be reached in browser by [http://127.0.0.1](http://127.0.0.1) or [https://127.0.0.1](https://127.0.0.1)
Default port mapping is (see `.env` file):

        Name                         Command                                            Ports
    --------------------------------------------------------------------------------------------------------------------------------------------
        hf_pimcore_db_1              docker-entrypoint.sh mysql ...       0.0.0.0:3307->3306/tcp,:::3307->3306/tcp                              
        hf_pimcore_elasticsearch_1   /bin/tini -- /usr/local/bi ...       9200/tcp, 9300/tcp                                                    
        hf_pimcore_php_1             /usr/local/bin/entrypoint. ...       9000/tcp                                                              
        hf_pimcore_redis_1           docker-entrypoint.sh redis ...       0.0.0.0:63791->6379/tcp,:::63791->6379/tcp                            
        hf_pimcore_webserver_1       nginx                                0.0.0.0:443->443/tcp,:::443->443/tcp, 0.0.0.0:80->80/tcp,:::80->80/tcp

To run tests:

    make test

Or you can run tests with code coverage:

    make test-coverage

Use `docker-compose exec php <command>` command to run any commands inside the container or to connect via `sh`:

    docker-compose exec php /bin/sh
