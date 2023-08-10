#!/bin/sh
if [ -z "${APP_KEY}" ]; then
    echo "APP_KEY environment variable is not set. Using .env.example as .env."
    mv .env.example .env
    rm -f ./genEnv.php
else
    echo "APP_KEY environment variable is set. Generating .env from System Environment."
    php -f genEnv.php
    rm -f ./.env.example
fi
/usr/bin/supervisord -c "/etc/supervisord.conf"
