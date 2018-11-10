#!/bin/sh

php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

php-fpm7 -F