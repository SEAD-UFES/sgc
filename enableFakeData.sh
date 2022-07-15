#!/bin/sh
sed -i 's/APP_ENV=production/APP_ENV=testing/g' .env
apk add --no-cache php81-curl php81-iconv
composer install
