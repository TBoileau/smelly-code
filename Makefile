phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

phpinsights:
	vendor/bin/phpinsights --no-interaction

phpmd:
	vendor/bin/phpmd src/ text .phpmd.xml

phpcpd:
	vendor/bin/phpcpd src/

analyse:
	make phpmd
	make phpcpd
	make phpinsights
	make phpstan
