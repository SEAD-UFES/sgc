# Based on https://www.reddit.com/r/laravel/comments/pqw6du/deploying_laravel_mix_using_docker/

# ============ PHP Dependencies ============ #
FROM composer:latest AS vendor
LABEL image=composer:latest

COPY . /app

# Clean up
RUN rm -rf ./resources/js/
RUN rm -rf ./resources/scss/
RUN rm -rf ./.k8s/
RUN rm -rf ./design/
RUN rm -f ./package-lock.json
RUN rm -f ./package.json
RUN rm -f ./phpstan.neon
RUN rm -f ./rector.php
RUN rm -f ./tsconfig.json
RUN rm -f ./vite.config.js

RUN composer remove barryvdh/laravel-debugbar brianium/paratest facade/ignition laravel/sail mockery/mockery nunomaduro/collision nunomaduro/larastan nunomaduro/phpinsights phpunit/php-code-coverage phpunit/phpunit squizlabs/php_codesniffer --dev --ignore-platform-reqs --no-interaction --no-scripts

RUN composer update \
    --ignore-platform-reqs \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev
    
# ===================================== #
FROM node:alpine AS frontend
LABEL image=node:alpine
RUN mkdir -p /laravel-app/public

COPY ./package.json /laravel-app
COPY ./package-lock.json /laravel-app
COPY ./tsconfig.json /laravel-app
COPY ./vite.config.js /laravel-app
COPY ./app/ /laravel-app

# Copy your JavaScript source files
COPY ./resources/ /laravel-app/resources/

WORKDIR /laravel-app
RUN npm ci && npm run build

# ===================================== #
FROM alpine:latest AS deploy

RUN apk update && apk upgrade --no-cache

# Explicitly set Timezone
ENV TIMEZONE=Brazil/East
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/$TIMEZONE /etc/localtime && \
    echo $TIMEZONE >  /etc/timezone && \
    apk del tzdata

# Change ICU lib data to full version
RUN apk del icu-data-en && \
    apk add --no-cache icu-data-full

# Install Nginx
RUN apk add --no-cache nginx

# Creating new user and group 'www' for nginx
RUN adduser -D -g 'www' www

# Create a directory for html files
RUN mkdir /www && \
    chown -R www:www /var/lib/nginx && \
    chown -R www:www /www

RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.orig
COPY ./.docker/nginx.conf /etc/nginx/nginx.conf

# Installing PHP
RUN apk add --no-cache php82 php82-fpm && \
    #Laravel site declared dependencies
    apk add --no-cache php82-bcmath php82-ctype php82-fileinfo php82-json php82-mbstring php82-openssl php82-pdo php82-tokenizer php82-xml && \
    #Dependencies that brake Laravel
    apk add --no-cache php82-gd php82-intl php82-pdo_mysql && \
    #More Dependencies that brake Laravel on run time
    apk add --no-cache php82-session php82-dom php82-simplexml php82-xmlwriter php82-xmlreader && \
    #More Dependencies... Composer with php82 this time
    apk add --no-cache php82-phar && \
    #More Dependencies... Required for laravel-backup
    apk add --no-cache php82-zip mariadb-connector-c

RUN ln -s /usr/bin/php82 /usr/bin/php && \
    ln -s /usr/sbin/php-fpm82 /usr/sbin/php-fpm && \
    ln -s /etc/php82 /etc/php && \
    ln -s /var/log/php82 /var/log/php

# Defining ENV variables which will be used in configuration
ENV PHP_FPM_USER="www"
ENV PHP_FPM_GROUP="www"
ENV PHP_FPM_LISTEN_MODE="0660"
ENV PHP_MEMORY_LIMIT="512M"
ENV PHP_MAX_UPLOAD="50M"
ENV PHP_MAX_FILE_UPLOAD="200"
ENV PHP_MAX_POST="100M"
ENV PHP_DISPLAY_ERRORS="On"
ENV PHP_DISPLAY_STARTUP_ERRORS="On"
ENV PHP_ERROR_REPORTING="E_COMPILE_ERROR\|E_RECOVERABLE_ERROR\|E_ERROR\|E_CORE_ERROR"
ENV PHP_CGI_FIX_PATHINFO=0

# Modifying configuration file php-fpm.conf
RUN sed -i "s|;listen.owner\s*=\s*nobody|listen.owner = $PHP_FPM_USER|g" /etc/php/php-fpm.conf && \
    sed -i "s|;listen.group\s*=\s*nobody|listen.group = $PHP_FPM_GROUP|g" /etc/php/php-fpm.conf && \
    sed -i "s|;listen.mode\s*=\s*0660|listen.mode = $PHP_FPM_LISTEN_MODE|g" /etc/php/php-fpm.conf && \
    sed -i "s|user\s*=\s*nobody|user = $PHP_FPM_USER|g" /etc/php/php-fpm.conf && \
    sed -i "s|group\s*=\s*nobody|group = $PHP_FPM_GROUP|g" /etc/php/php-fpm.conf && \
    sed -i "s|;log_level\s*=\s*notice|log_level = notice|g" /etc/php/php-fpm.conf && \
    sed -i "s|;daemonize = yes|daemonize = no|g" /etc/php/php-fpm.conf

# Modifying configuration file php.ini
RUN sed -i "s|display_errors\s*=\s*Off|display_errors = $PHP_DISPLAY_ERRORS|i" /etc/php/php.ini && \
    sed -i "s|display_startup_errors\s*=\s*Off|display_startup_errors = $PHP_DISPLAY_STARTUP_ERRORS|i" /etc/php/php.ini && \
    sed -i "s|error_reporting\s*=\s*E_ALL & ~E_DEPRECATED & ~E_STRICT|error_reporting = $PHP_ERROR_REPORTING|i" /etc/php/php.ini && \
    sed -i "s|;*memory_limit =.*|memory_limit = $PHP_MEMORY_LIMIT|i" /etc/php/php.ini && \
    sed -i "s|;*upload_max_filesize =.*|upload_max_filesize = $PHP_MAX_UPLOAD|i" /etc/php/php.ini && \
    sed -i "s|;*max_file_uploads =.*|max_file_uploads = $PHP_MAX_FILE_UPLOAD|i" /etc/php/php.ini && \
    sed -i "s|;*post_max_size =.*|post_max_size = $PHP_MAX_POST|i" /etc/php/php.ini && \
    sed -i "s|;*cgi.fix_pathinfo=.*|cgi.fix_pathinfo= $PHP_CGI_FIX_PATHINFO|i" /etc/php/php.ini

# /etc/php/php-fpm.d/www.conf
RUN sed -i "s|user\s*=\s*nobody|user = $PHP_FPM_USER|g" /etc/php/php-fpm.d/www.conf && \
    sed -i "s|group\s*=\s*nobody|group = $PHP_FPM_GROUP|g" /etc/php/php-fpm.d/www.conf

COPY --chown=www:www --from=vendor /app/ /www/

# COPY --chown=www:www --from=frontend /laravel-app/public/js/ /www/public/js/
# COPY --chown=www:www --from=frontend /laravel-app/public/css/ /www/public/css/
COPY --chown=www:www --from=frontend /laravel-app/public/build/ /www/public/build/



RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

## RUN cp /usr/bin/php82 /usr/bin/php

WORKDIR /www
RUN composer dump-autoload --optimize --no-dev

#COPY --chown=www:www ./.env.deploy /www/.env

RUN mkdir /www/storage/framework/cache/data && \
    chown www:www /www/storage/framework/cache/data

#RUN php artisan key:generate

RUN php artisan optimize:clear && \
    php artisan optimize && \
    php artisan view:cache && \
    php artisan config:clear

# Install Supervisor Process Control System
RUN apk add --no-cache supervisor
COPY ./.docker/supervisord.conf /etc/supervisord.conf

# Install mysqldump to backup database
RUN apk add --no-cache mysql-client

# Add cron job to run Laravel Scheduler every minute
RUN echo "* * * * * cd /www && php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontabs/root

# RTAs

RUN touch /var/log/nginx/access.log && \
    chown www:www /var/log/nginx/access.log && \
    touch /var/lib/nginx/logs/error.log && \
    chown www:www /var/lib/nginx/logs/error.log && \
    touch /var/run/nginx/nginx.pid && \
    chown www:www /var/run/nginx/nginx.pid && \
    touch /var/log/php/error.log && \
    chown www:www /var/log/php/error.log && \
    touch /www/storage/logs/laravel.log && \
    chown www:www /www/storage/logs/laravel.log

RUN rm -f /etc/nginx/http.d/default.conf && \
    echo "APP_BUILD=$(date +%Y%m%d_%H%M)" > BUILD

RUN chmod 555 /www/entrypoint.sh && \
    chmod 655 /www/disableHttpsRequirement.sh && \
    chmod 655 /www/enableFakeData.sh && \
    chmod 655 /www/genEnv.php

# Clean up
RUN rm -f ./.env.ci && \
    rm -f ./.env.deploy

EXPOSE 8080

# Keep Docker Container Running for Debugging
# ENTRYPOINT ["tail", "-f", "/dev/null"]

ENTRYPOINT ["/www/entrypoint.sh"]
