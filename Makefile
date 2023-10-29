## —— Test API —————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: ## Install vendors according to the current composer.lock file
	composer install

update: ## Update vendors according to the current composer.json file
	composer update

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

purge: ## Purge cache and logs
	rm -rf var/logs/*.log
	ls var | grep -v logs | xargs -I % sh -c 'rm -rf var/%'

code-check: ## Check code before up to repository (pre-commit)
	bin/php-cs-fixer --verbose fix && git add .
	bin/phpunit

cs: ## Launch check style and static analysis
	bin/php-cs-fixer --no-interaction --dry-run --diff -v fix

cs-fix: ## Executes cs fixer
	bin/php-cs-fixer --no-interaction --diff -v fix

test-unit: phpunit.xml.dist ## Launch all unit tests
	bin/phpunit --stop-on-failure --testdox

test-fun: ## Launch all functional tests
	bin/codecept run functional

test-all: ## Launch all functional and unit tests
	bin/codecept run
