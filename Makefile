.PHONY: install build analyse phpstan phpinsights phpcpd phpmd tests

install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	composer install
	make prepare env=$(env)
	yarn install
	yarn run dev

phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

phpinsights:
	vendor/bin/phpinsights --no-interaction

phpmd:
	vendor/bin/phpmd src/ text .phpmd.xml

phpcpd:
	vendor/bin/phpcpd src/

composer:
	composer valid

analyse:
	make composer
	make phpmd
	make phpcpd
	make phpinsights
	make phpstan

fixtures:
	php bin/console doctrine:fixtures:load -n --env=$(env)

database:
	php bin/console doctrine:database:drop --if-exists --force --env=$(env)
	php bin/console doctrine:database:create --env=$(env)
	php bin/console doctrine:schema:update --force --env=$(env)

prepare:
	make database env=$(env)
	make fixtures env=$(env)

tests:
	php bin/phpunit --testdox

fix:
	vendor/bin/php-cs-fixer fix
