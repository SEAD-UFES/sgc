# Based on https://www.reddit.com/r/laravel/comments/pqw6du/deploying_laravel_mix_using_docker/

# ============ PHP Dependencies ============ #
FROM composer:latest as vendor
LABEL image=composer:latest

COPY . /app

RUN composer remove barryvdh/laravel-debugbar brianium/paratest facade/ignition laravel/sail mockery/mockery nunomaduro/collision nunomaduro/larastan nunomaduro/phpinsights phpunit/php-code-coverage phpunit/phpunit squizlabs/php_codesniffer --dev --ignore-platform-reqs --no-interaction --no-scripts

RUN composer update \
    --ignore-platform-reqs \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev
    
# ===================================== #
FROM node:alpine as frontend
LABEL image=node:alpine
RUN mkdir -p /app/public

COPY package.json package-lock.json webpack.mix.js /app/

# Copy your JavaScript source files
COPY resources/ /app/resources/

WORKDIR /app
RUN npm ci && npm run build

# ===================================== #
FROM alpine:latest as deploy

RUN apk update && apk upgrade --no-cache

# Explicitly set Timezone
ENV TIMEZONE=Brazil/East
RUN apk add --no-cache tzdata
RUN cp /usr/share/zoneinfo/$TIMEZONE /etc/localtime
RUN echo $TIMEZONE >  /etc/timezone
RUN apk del tzdata

# Change ICU lib data to full version
RUN apk del icu-data-en
RUN apk add --no-cache icu-data-full

# Install Nginx
RUN apk add --no-cache nginx

# Creating new user and group 'www' for nginx
RUN adduser -D -g 'www' www

# Create a directory for html files
RUN mkdir /www
RUN chown -R www:www /var/lib/nginx
RUN chown -R www:www /www

RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.orig
COPY ./.docker/nginx.conf /etc/nginx/nginx.conf

# Installing PHP
RUN apk add --no-cache php81 php81-fpm

# # RUN ln -s /usr/bin/php81 /usr/bin/php
RUN ln -s /usr/sbin/php-fpm81 /usr/sbin/php-fpm

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
RUN sed -i "s|;listen.owner\s*=\s*nobody|listen.owner = $PHP_FPM_USER|g" /etc/php81/php-fpm.conf
RUN sed -i "s|;listen.group\s*=\s*nobody|listen.group = $PHP_FPM_GROUP|g" /etc/php81/php-fpm.conf
RUN sed -i "s|;listen.mode\s*=\s*0660|listen.mode = $PHP_FPM_LISTEN_MODE|g" /etc/php81/php-fpm.conf
RUN sed -i "s|user\s*=\s*nobody|user = $PHP_FPM_USER|g" /etc/php81/php-fpm.conf
RUN sed -i "s|group\s*=\s*nobody|group = $PHP_FPM_GROUP|g" /etc/php81/php-fpm.conf
RUN sed -i "s|;log_level\s*=\s*notice|log_level = notice|g" /etc/php81/php-fpm.conf

RUN sed -i "s|;daemonize = yes|daemonize = no|g" /etc/php81/php-fpm.conf

# Modifying configuration file php.ini
RUN sed -i "s|display_errors\s*=\s*Off|display_errors = $PHP_DISPLAY_ERRORS|i" /etc/php81/php.ini
RUN sed -i "s|display_startup_errors\s*=\s*Off|display_startup_errors = $PHP_DISPLAY_STARTUP_ERRORS|i" /etc/php81/php.ini
RUN sed -i "s|error_reporting\s*=\s*E_ALL & ~E_DEPRECATED & ~E_STRICT|error_reporting = $PHP_ERROR_REPORTING|i" /etc/php81/php.ini
RUN sed -i "s|;*memory_limit =.*|memory_limit = $PHP_MEMORY_LIMIT|i" /etc/php81/php.ini
RUN sed -i "s|;*upload_max_filesize =.*|upload_max_filesize = $PHP_MAX_UPLOAD|i" /etc/php81/php.ini
RUN sed -i "s|;*max_file_uploads =.*|max_file_uploads = $PHP_MAX_FILE_UPLOAD|i" /etc/php81/php.ini
RUN sed -i "s|;*post_max_size =.*|post_max_size = $PHP_MAX_POST|i" /etc/php81/php.ini
RUN sed -i "s|;*cgi.fix_pathinfo=.*|cgi.fix_pathinfo= $PHP_CGI_FIX_PATHINFO|i" /etc/php81/php.ini

# /etc/php81/php-fpm.d/www.conf
RUN sed -i "s|user\s*=\s*nobody|user = $PHP_FPM_USER|g" /etc/php81/php-fpm.d/www.conf
RUN sed -i "s|group\s*=\s*nobody|group = $PHP_FPM_GROUP|g" /etc/php81/php-fpm.d/www.conf

#Laravel site declared dependencies
RUN apk add --no-cache php81-bcmath php81-ctype php81-fileinfo php81-json php81-mbstring php81-openssl php81-pdo php81-tokenizer php81-xml

#Dependencies that brake Laravel
RUN apk add --no-cache php81-gd php81-intl php81-pdo_mysql

#More Dependencies that brake Laravel on run time
RUN apk add --no-cache php81-session php81-dom php81-simplexml php81-xmlwriter php81-xmlreader

#More Dependencies... Composer with php81 this time
RUN apk add --no-cache php81-phar

#More Dependencies... Required for laravel-backup
RUN apk add --no-cache php81-zip mariadb-connector-c

COPY --chown=www:www --from=vendor /app/ /www/

COPY --chown=www:www --from=frontend /app/public/js/ /www/public/js/
COPY --chown=www:www --from=frontend /app/public/css/ /www/public/css/



RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

RUN cp /usr/bin/php81 /usr/bin/php

WORKDIR /www
RUN composer dump-autoload --optimize --no-dev

#COPY --chown=www:www ./.env.deploy /www/.env

RUN mkdir /www/storage/framework/cache/data
RUN chown www:www /www/storage/framework/cache/data

#RUN php artisan key:generate

RUN php artisan optimize:clear
RUN php artisan optimize
RUN php artisan view:cache
RUN php artisan config:clear

# Install Supervisor Process Control System
RUN apk add --no-cache supervisor
COPY ./.docker/supervisord.conf /etc/supervisord.conf

# Install mysqldump to backup database
RUN apk add --no-cache mysql-client

# Add cron job to run Laravel Scheduler every minute
RUN echo "* * * * * cd /www && php artisan schedule:run >> /dev/null 2>&1" >> /etc/crontabs/root

# RTAs

RUN touch /var/log/nginx/access.log
RUN chown www:www /var/log/nginx/access.log
#RUN chmod 666 /var/log/nginx/access.log

RUN touch /var/lib/nginx/logs/error.log
RUN chown www:www /var/lib/nginx/logs/error.log
#RUN chmod 666 /var/lib/nginx/logs/error.log

RUN touch /var/run/nginx/nginx.pid
RUN chown www:www /var/run/nginx/nginx.pid
#RUN chmod 666 /var/run/nginx/nginx.pid

RUN touch /var/log/php81/error.log
RUN chown www:www /var/log/php81/error.log
#RUN chmod 666 /var/log/php81/error.log

RUN touch /www/storage/logs/laravel.log
RUN chown www:www /www/storage/logs/laravel.log
#RUN chmod 666 /www/storage/logs/laravel.log

RUN rm -f /etc/nginx/http.d/default.conf
RUN echo "APP_BUILD=$(date +%Y%m%d_%H%M)" > BUILD

RUN chmod 555 /www/entrypoint.sh
RUN chmod 555 /www/disableHttpsRequirement.sh
RUN chmod 555 /www/enableFakeData.sh

EXPOSE 8080
ENTRYPOINT ["/www/entrypoint.sh"]
