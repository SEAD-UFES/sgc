#!/bin/sh
sed -i 's/APP_ENV=production/APP_ENV=testing/g' .env
apk add --no-cache php82-curl php82-iconv
composer install
rm "$0"