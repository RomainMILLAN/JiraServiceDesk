#
# Made by Romain Millan © 2025
#
.DEFAULT_GLOBAL = help
SHELL:=/bin/bash

DOCKER=docker
DC=$(DOCKER) compose
DCE=$(DC) exec
PHP=$(DCE) php php
CONSOLE=$(PHP) bin/console
COMPOSER=$(DCE) php composer
NPM=npm
ENV ?= dev

.PHONY: help
help:
	@grep -E '(^([a-zA-Z_-]+ ?)+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## Made by Romain MILLAN
##

##
## —— Environment ⚙️ ————————————————
.PHONY: start
start: build up vendor assets  ## Start project

.PHONY: stop
stop: ## Stop project
	@$(DC) down --remove-orphans
	@rm -rf vendor
	@rm -rf node_modules

.PHONY: up
up: build
	@SERVER_NAME=:80 $(DC) up --pull always -d

.PHONY: build
build:
	@$(DC) build --pull --no-cache

QUEUE_NAME ?= async
VERBOSITY ?= -v
.PHONY: consume
consume: ## Run an async messenger consumer
	@$(CONSOLE) messenger:consume $(QUEUE_NAME) $(VERBOSITY)

##
## —— Dependencies 🔒️————————————————
.PHONY: composer
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

.PHONY: vendor
vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress
vendor: composer

.PHONY: vendor-build
vendor-build:
	@$(DC) php composer install --no-dev --optimize-autoloader

.PHONY: npm
npm:
	$(NPM) install

##
## —— Cache 🗃️ ————————————————
.PHONY: cc
cc:			## Clear cache
	$(CONSOLE) ca:cl -e $(or $(ENV), 'dev')

##
## —— Assets ✨ ————————————————
.PHONY: assets
assets:	npm  ## Build assets - dev version
	$(NPM) run dev

.PHONY: assets-build
assets-build: npm  ## Build assets - prod version
	$(NPM) run build

.PHONY: watch
watch:		## Watch assets
	$(NPM) run watch

##
## —— Database 🗃️————————————————
.PHONY: db-diff
db-diff: ## Generate a new migration
	@$(CONSOLE) doctrine:migration:diff

.PHONY: db-migrate
db-migrate: ## Execute all not migrate migrations
	@$(CONSOLE) doctrine:migration:migrate --no-interaction

db-fixtures: ## Load fixtures
	@$(CONSOLE) doctrine:fixtures:load -n --append

.PHONY: db-reset
db-reset: ## Reset database and execute migrations
	@echo "💥 Drop the database."
	@$(CONSOLE) doctrine:database:drop --force
	@echo "🏗️ Create new database."
	@$(CONSOLE) doctrine:database:create
	@echo "🚚 Run all migrations."
	@make db-migrate

.PHONY: db-import
db-import: ## Reset database with given DUMP variable
	@:$(call check_defined, DUMP, sql file)
	@docker cp ./var/$(DUMP) $(shell $(DC) ps -q database):/$(DUMP)
	@echo '🗃️ Reseting and import database.'
	@$(DCE) database reset $(DUMP) > /dev/null
	@echo '✅ Your dump ($(DUMP)) is been imported.'

.PHONY: db-dump
db-dump: ## Save database to a sql file
	@:$(call check_defined, DUMP, sql file)
	@echo '🗃️ Saving database.'
	@$(DCE) database save $(DUMP) > /dev/null
	@echo '🗃️ Copy to local.'
	@docker cp $(shell $(DC) ps -q database):/$(DUMP) ./var/$(DUMP)

##
## —— Tests 📊 & Code Quality ✅————————————————
.PHONY: test
tests: quality phpunit infection ## Runs quality code & tests

.PHONY: phpunit
phpunit: ## Runs unit tests
ifdef FILTER
	@APP_ENV=test $(PHP) vendor/bin/phpunit --filter $(FILTER)
else ifdef COVERAGE
	@APP_ENV=test XDEBUG_MODE=coverage $(PHP) vendor/bin/phpunit --coverage-html var/phpunit-coverage
else
	@APP_ENV=test $(PHP) vendor/bin/phpunit
endif

.PHONY: infection
infection:
	@APP_ENV=test XDEBUG_MODE=coverage $(PHP) vendor/bin/infection --min-msi=100 --min-covered-msi=100 --threads=8

.PHONY: quality
quality: ecs ## Run quality code tools

.PHONY: ecs
ecs:		## Coding standards
	@$(PHP) vendor/bin/ecs check --fix

##
## —— Configuration 📋 ————————————————
COMPOSER_FILE=./composer.json
PACKAGE_FILE=./package.json
ENV_FILE=./.env

deploy: vendor-build assets-build
	@echo "🚀 Deploying project"
	@echo "📝 Dumping environment variables"
	@composer dump-env $(ENV)
	@echo "🚚 Running migrations"
	@php bin/console doctrine:migrations:migrate --no-interaction
	@echo "🌐 Clearing cache"
	@php bin/console cache:clear
	@echo "🌐 Warmup cache"
	@php bin/console cache:warmup

bump:
	@if [ -z "$(VERSION)" ]; then \
		echo "Erreur : Vous devez fournir une nouvelle version avec VERSION=x.x.x"; \
		exit 1; \
	fi
	@echo "Mise à jour de la version dans composer.json, package.json et .env vers $(VERSION)"
	@sed -i '' 's/"version": *"[0-9]*\.[0-9]*\.[0-9]*"/"version": "$(VERSION)"/' $(COMPOSER_FILE)
	@sed -i '' 's/"version": *"[0-9]*\.[0-9]*\.[0-9]*"/"version": "$(VERSION)"/' $(PACKAGE_FILE)
	@sed -i '' 's/^PROJECT_VERSION=.*/PROJECT_VERSION=$(VERSION)/' $(ENV_FILE)

SERVER ?= rmjsd.p
DOMAIN ?= rmjsd
prod:
	@echo "🚀 Deploying in production project."
	@ssh -A $(SERVER) 'cd $(DOMAIN) && git restore package-lock.json && ./restart-prod.sh'

