#!make
include .env
export $(shell sed 's/=.*//' .env)

build:
	@make start
	@make npm-install
	@make db-import

start:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose pull
	docker-compose up -d --build --remove-orphans

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

npm-install:
	docker-compose exec node sh -c 'npm install --legacy-peer-deps'

open:
	open ${PROJECT_BASE_URL}:${PROJECT_PORT}

in:
	docker-compose exec php bash

DB_CONN=mysql -h$${DB_HOST} -u$${DB_USER} -p$${DB_PASSWORD} $${DB_NAME}
db-drop:
	docker-compose exec php sh -c "echo truncate details | $(DB_CONN)"

db-import:
	docker-compose exec php sh -c "cat /database/dump.sql | $(DB_CONN)"

FIELDS=`id`, `url`, `c_url`, `timestamp`, `server name`, `perfdata`, `type`, `cookie`, `post`, `get`, `pmu`, `wt`, `cpu`, `server_id`, `aggregateCalls_include`
D_VALUES='123', '/dummy.php', '/dummy.php', '2016-11-08 23:03:23', 'local', 0x785e85d0df0a83201406f077f16abbdb39d6fe1c69f426c3b66241abd06083d1bbef6436042fbc113ef1e7a747d385be9617d10c43515c2b6d84d284cb2692b84f42b5942b17de2e60ae664bc0791576325ddd2710380324f8feb5a5849864610fac3dd29912bcc258c9509d5d9147e8918c11864886487a94c5084274d8c6f0d26dbfdbb3e279244c765a10f2f3fee8f31ccdd0dc1eadd5555727bceb3c6e3a7138e76fcd3f8dfc8a0a, 0, 0x613a313a7b733a363a226861735f6a73223b733a313a2231223b7d, 0x613a313a7b733a373a22536b6970706564223b733a32353a22506f73742064617461206f6d69747465642062792072756c65223b7d, 0x613a303a7b7d, 0, 53, 0, 'mys', ''
db-import-dummy:
	docker-compose exec php sh -c "echo \"INSERT INTO details VALUES (${D_VALUES});\" | $(DB_CONN)"

TRACE=$(shell cat docker/traces/1.xhprof)
T_VALUES='1234', '/trace.php', '/trace.php', '2016-11-08 23:03:23', 'local', '$(shell cat docker/traces/1.xhprof)', 0, 0x613a313a7b733a363a226861735f6a73223b733a313a2231223b7d, 0x613a313a7b733a373a22536b6970706564223b733a32353a22506f73742064617461206f6d69747465642062792072756c65223b7d, 0x613a303a7b7d, 0, 53, 0, 'mys', ''
db-import-traces:
	docker-compose exec php sh -c "echo \"INSERT INTO details VALUES (\" > /database/tmp.sql"
	docker-compose exec php sh -c "echo \"'1234', '/trace.php', '/trace.php', '2016-11-08 23:03:23', 'local', '\" >> /database/tmp.sql"
	docker-compose exec php sh -c "cat /traces/1.xhprof >> /database/tmp.sql"
	docker-compose exec php sh -c "echo \"', 0, 0x613a313a7b733a363a226861735f6a73223b733a313a2231223b7d, 0x613a313a7b733a373a22536b6970706564223b733a32353a22506f73742064617461206f6d69747465642062792072756c65223b7d, 0x613a303a7b7d, 0, 53, 0, 'mys', ''\" >> /database/tmp.sql"
	docker-compose exec php sh -c "echo \")\" >> /database/tmp.sql"
	docker-compose exec php sh -c "cat /database/tmp.sql | $(DB_CONN)"

sqlc:
	docker-compose exec php sh -c "$(DB_CONN)"
