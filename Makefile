#!make
include .env
export $(shell sed 's/=.*//' .env)

build:
	@make start
	@make composer-install
	@make db-install

start:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose pull
	docker-compose up -d --build --remove-orphans

composer-install:
	docker-compose exec php sh -c "composer install"
	@make db-install

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

open:
	open ${PROJECT_BASE_URL}:${PROJECT_PORT}

in:
	docker-compose exec php bash

DB_CONN=mysql -h$${DB_HOST} -u$${DB_USER} -p$${DB_PASS} $${DB_NAME}
db-drop:
	docker-compose exec php sh -c "echo truncate details | $(DB_CONN)"

db-install:
	docker-compose exec php sh -c "cat /database/install.sql | $(DB_CONN)"

db-dummy-data:
	docker-compose exec php sh -c "cat /database/dummy.sql | $(DB_CONN)"

sqlc:
	docker-compose exec php sh -c "$(DB_CONN)"

test:
	docker-compose exec php sh -c "cd tests && ../vendor/bin/behat"
test-tag:
	docker-compose exec php sh -c "cd tests && ../vendor/bin/behat --tags=$(tags)"
dry-run:
	docker-compose exec php sh -c "cd tests && ../vendor/bin/behat --dry-run"
