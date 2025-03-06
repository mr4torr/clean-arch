APP_NAME=app
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # Development # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# ./bin/remote $(APP_NAME) "mkdir -p ./runtime/container && rm -rf ./runtime/container/* && chown 1000:1000 -R ./runtime/container"
# ./bin/remote $(APP_NAME) "mkdir -p ./runtime/logs && rm -rf ./runtime/logs/* && chown 1000:1000 -R ./runtime/logs"
# ./bin/remote $(APP_NAME) "php bin/hyperf.php list -q"

.PHONY: clear
clear:
	./bin/remote $(APP_NAME) "mkdir -p ./runtime/container && rm -rf ./runtime/container/* && chmod 777 -R ./runtime/container"
	./bin/remote $(APP_NAME) "mkdir -p ./runtime/logs && echo -n "" > dev.log && chmod 777 -R ./runtime/logs"
	./bin/remote $(APP_NAME) "php bin/hyperf.php list -q"

# bash -c "env UID=1000 GID=1000 docker compose -f docker-compose.dev.yml up -d; ./bin/watch; docker compose down; pkill fswatch"
.PHONY: dev
dev:
	# bash -c "docker compose -f docker-compose.dev.yml up -d; make clear; ./bin/watch; docker compose down; pkill fswatch"
	bash -c "docker compose -f docker-compose.dev.yml up;"

.PHONY: test
test:
	# bash -c "docker compose -f docker-compose.dev.yml up -d; make clear; ./bin/watch; docker compose down; pkill fswatch"
	bash -c "bin/remote app \"composer test -- $@\""

# bash -c "env UID=1000 GID=1000 docker compose -f docker-compose.dev.yml up -d; ./bin/watch; docker compose down; pkill fswatch"
.PHONY: log
log:
	tail -f ./runtime/logs/dev.log


.PHONY: stop
stop:
	docker compose down

.PHONY: remote
remote:
	./bin/remote $(APP_NAME) bash

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # Production # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
.PHONY: prod
prod:
	docker compose -f docker-compose.yml up -d
