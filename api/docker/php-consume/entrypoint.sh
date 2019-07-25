#!/bin/sh
sleep 10;
bin/console messenger:consume >&1;
exec docker-php-entrypoint "$@"
