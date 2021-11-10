.PHONY: install build analyse phpstan phpinsights phpcpd phpmd tests eslint stylelint

install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	composer install
	make prepare env=$(env)
	yarn install
	yarn run dev

eslint:
	npx eslint assets/

stylelint:
	npx stylelint "assets/styles/**/*.scss"

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

twig:
	php bin/console lint:twig templates
	vendor/bin/twigcs templates

doctrine:
	php bin/console doctrine:schema:valid --skip-sync

analyse:
	make composer
	make eslint
	make stylelint
	make twig
	make phpmd
	make phpcpd
	make doctrine
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
	npx eslint assets/ --fix
	npx stylelint "assets/styles/**/*.scss" --fix
	vendor/bin/php-cs-fixer fix
