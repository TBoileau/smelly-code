phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon src --no-progress

analyse:
	make phpstan
