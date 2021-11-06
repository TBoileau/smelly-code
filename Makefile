phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

phpinsights:
	vendor/bin/phpinsights --no-interaction

phpcpd:
	vendor/bin/phpcpd src/

analyse:
	make phpcpd
	make phpinsights
	make phpstan
