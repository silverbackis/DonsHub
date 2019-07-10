#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
	if [ "$APP_ENV" != 'prod' ]; then
		PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
	fi
	ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

	mkdir -p var/cache var/log config/jwt
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

	# Generate keys for JWT
	if [ ! -f $JWT_SECRET_KEY ]; then
    openssl genrsa -out $JWT_SECRET_KEY -aes256 -passout pass:$JWT_PASSPHRASE 4096
    openssl rsa -passin pass:$JWT_PASSPHRASE -pubout -in $JWT_SECRET_KEY -out $JWT_PUBLIC_KEY
  fi

	if [ "$APP_ENV" != 'prod' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
	fi

	echo "Waiting for db to be ready..."
	until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
	done

	if [ "$APP_ENV" != 'prod' ]; then
		bin/console doctrine:schema:update --force --no-interaction
	fi

	bin/console app:fetch:matches || EXIT_CODE=$? && true
  echo ${EXIT_CODE}

  bin/console app:fetch:tweets || EXIT_CODE=$? && true
  echo ${EXIT_CODE}
fi

exec docker-php-entrypoint "$@"
