COMPOSE_EXEC= docker-compose exec -T -u $$(id -u)
DB_EXEC= $(COMPOSE_EXEC) db
APP_EXEC= $(COMPOSE_EXEC) php
CONSOLE= $(APP_EXEC) bin/console

install: build-containers install-dependencies db-import rebuild-pimcore-classes
build: build-containers install-dependencies rebuild-pimcore-classes
test: build-containers configure-test-project run-unit-tests

build-containers:
	USER_ID=$$(id -u) docker-compose up -d --build

start:
	docker-compose up -d

stop:
	docker-compose stop

install-dependencies:
	$(APP_EXEC) sh -c "composer install"

rebuild-pimcore-classes:
	$(CONSOLE) pimcore:deployment:classes-rebuild -d -c -n

enter-php:
	$(APP_EXEC) bash

clean:
	rm -rf Source/tmp

# @todo Need to check this command usage. Maybe we can remove it to prevent mysql credentials duplication
db-export:
	$(DB_EXEC) mysqldump --lock-tables=false --skip-add-locks --force -upimcore -ppimcore  pimcore | sed '/\(SQL SECURITY DEFINER\|mysqldump\)/d' > Source/db-dumps/dump.sql

db-import:
	$(CONSOLE) doctrine:database:import db-dumps/dump.sql

configure-test-project:
	$(APP_EXEC) sh -c "APP_ENV=test composer install"
	$(CONSOLE) doctrine:database:drop --if-exists --force --env=test
	$(CONSOLE) doctrine:database:create --if-not-exists --env=test
	$(CONSOLE) doctrine:database:import db-dumps/dump.sql --env=test
	$(APP_EXEC) dockerize -wait tcp://elasticsearch:9200 -timeout 60s -wait-retry-interval 5s
	$(CONSOLE) pimcore:deployment:classes-rebuild -d -c -n --env=test

run-unit-tests:
	$(APP_EXEC) sh -c "php -d memory_limit=-1 vendor/phpunit/phpunit/phpunit -v"

test-coverage: configure-test-project
	$(APP_EXEC) sh -c "php -d memory_limit=-1 vendor/phpunit/phpunit/phpunit -v --coverage-text"

