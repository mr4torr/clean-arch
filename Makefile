APP_NAME=app
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # Development # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# ./bin/remote $(APP_NAME) "mkdir -p ./runtime/container && rm -rf ./runtime/container/* && chown 1000:1000 -R ./runtime/container"
# ./bin/remote $(APP_NAME) "mkdir -p ./runtime/logs && rm -rf ./runtime/logs/* && chown 1000:1000 -R ./runtime/logs"
# ./bin/remote $(APP_NAME) "php bin/hyperf.php list -q"

.PHONY: clear
clear:
	sudo chmod 777 -R runtime/container
	./bin/remote $(APP_NAME) "mkdir -p ./runtime/container && rm -rf ./runtime/container/* && chmod 777 -R ./runtime/container"
	./bin/remote $(APP_NAME) "mkdir -p ./runtime/logs && chmod 777 -R ./runtime/logs"
	./bin/remote $(APP_NAME) "php bin/hyperf.php list -q"

# bash -c "env UID=1000 GID=1000 docker compose -f docker-compose.dev.yml up -d; ./bin/watch; docker compose down; pkill fswatch"
.PHONY: dev
dev:
	bash -c "docker compose -f docker-compose.dev.yml up;"

.PHONY: test
test:
	# bash -c "docker compose -f docker-compose.dev.yml up -d; make clear; ./bin/watch; docker compose down; pkill fswatch"
	bash -c "bin/remote $(APP_NAME) \"composer test -- $@\""

# bash -c "env UID=1000 GID=1000 docker compose -f docker-compose.dev.yml up -d; ./bin/watch; docker compose down; pkill fswatch"
.PHONY: log
log:
	tail -f ./runtime/logs/dev.log


.PHONY: stop
stop:
	docker compose down


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # Setup # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
.PHONY: setup
setup:
	./bin/console migrate:install;
	./bin/console app:migrate Auth

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # Migration # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
.PHONY: bas_migration
bas_migration:
	bash -c "bin/remote app \"php bin/hyperf.php gen:migration $@ --path=database/core/migrations\""

.PHONY: ten_migration
ten_migration:
	bash -c "bin/migration/tenant\""

.PHONY: remote
remote:
	./bin/remote $(APP_NAME) bash

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # Production # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
.PHONY: prod
prod:
	docker compose -f docker-compose.yml up -d
