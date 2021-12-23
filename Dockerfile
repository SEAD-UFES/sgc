# Based on https://www.reddit.com/r/laravel/comments/pqw6du/deploying_laravel_mix_using_docker/

# ============ PHP Dependencies ============ #
FROM composer:latest as vendor
LABEL image=composer:latest

COPY . /app

RUN composer remove barryvdh/laravel-debugbar brianium/paratest facade/ignition laravel/sail mockery/mockery nunomaduro/collision nunomaduro/phpinsights phpunit/php-code-coverage phpunit/phpunit squizlabs/php_codesniffer --dev --ignore-platform-reqs --no-interaction --no-scripts

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
RUN npm ci && npm run prod

# ===================================== #
FROM alpine:latest as deploy

RUN apk update && apk upgrade --no-cache

# Explicitly set Timezone
ENV TIMEZONE=Brazil/East
RUN apk add --no-cache tzdata
RUN cp /usr/share/zoneinfo/$TIMEZONE /etc/localtime
RUN echo $TIMEZONE >  /etc/timezone
RUN apk del tzdata

# Install Nginx
RUN apk add --no-cache nginx

# Creating new user and group 'www' for nginx
RUN adduser -D -g 'www' www

# Create a directory for html files
RUN mkdir /www
RUN chown -R www:www /var/lib/nginx
RUN chown -R www:www /www

RUN rm -f /etc/nginx/nginx.conf
COPY ./.docker/nginx.conf /etc/nginx/nginx.conf

# Installing PHP
RUN apk add --no-cache php8 php8-fpm

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
RUN sed -i "s|;listen.owner\s*=\s*nobody|listen.owner = $PHP_FPM_USER|g" /etc/php8/php-fpm.conf
RUN sed -i "s|;listen.group\s*=\s*nobody|listen.group = $PHP_FPM_GROUP|g" /etc/php8/php-fpm.conf
RUN sed -i "s|;listen.mode\s*=\s*0660|listen.mode = $PHP_FPM_LISTEN_MODE|g" /etc/php8/php-fpm.conf
RUN sed -i "s|user\s*=\s*nobody|user = $PHP_FPM_USER|g" /etc/php8/php-fpm.conf
RUN sed -i "s|group\s*=\s*nobody|group = $PHP_FPM_GROUP|g" /etc/php8/php-fpm.conf
RUN sed -i "s|;log_level\s*=\s*notice|log_level = notice|g" /etc/php8/php-fpm.conf

RUN sed -i "s|;daemonize = yes|daemonize = no|g" /etc/php8/php-fpm.conf

# Modifying configuration file php.ini
RUN sed -i "s|display_errors\s*=\s*Off|display_errors = $PHP_DISPLAY_ERRORS|i" /etc/php8/php.ini
RUN sed -i "s|display_startup_errors\s*=\s*Off|display_startup_errors = $PHP_DISPLAY_STARTUP_ERRORS|i" /etc/php8/php.ini
RUN sed -i "s|error_reporting\s*=\s*E_ALL & ~E_DEPRECATED & ~E_STRICT|error_reporting = $PHP_ERROR_REPORTING|i" /etc/php8/php.ini
RUN sed -i "s|;*memory_limit =.*|memory_limit = $PHP_MEMORY_LIMIT|i" /etc/php8/php.ini
RUN sed -i "s|;*upload_max_filesize =.*|upload_max_filesize = $PHP_MAX_UPLOAD|i" /etc/php8/php.ini
RUN sed -i "s|;*max_file_uploads =.*|max_file_uploads = $PHP_MAX_FILE_UPLOAD|i" /etc/php8/php.ini
RUN sed -i "s|;*post_max_size =.*|post_max_size = $PHP_MAX_POST|i" /etc/php8/php.ini
RUN sed -i "s|;*cgi.fix_pathinfo=.*|cgi.fix_pathinfo= $PHP_CGI_FIX_PATHINFO|i" /etc/php8/php.ini

# /etc/php8/php-fpm.d/www.conf
RUN sed -i "s|user\s*=\s*nobody|user = $PHP_FPM_USER|g" /etc/php8/php-fpm.d/www.conf
RUN sed -i "s|group\s*=\s*nobody|group = $PHP_FPM_GROUP|g" /etc/php8/php-fpm.d/www.conf

#Laravel site declared dependencies
RUN apk add --no-cache php8-bcmath php8-ctype php8-fileinfo php8-json php8-mbstring php8-openssl php8-pdo php8-tokenizer php8-xml

#Dependencies that brake Laravel
RUN apk add --no-cache php8-gd php8-intl php8-pdo_mysql

#More Dependencies that brake Laravel on run time
RUN apk add --no-cache php8-session php8-dom php8-simplexml php8-xmlwriter php8-xmlreader

COPY --chown=www:www --from=vendor /app/ /www/

COPY --chown=www:www --from=frontend /app/public/js/ /www/public/js/
COPY --chown=www:www --from=frontend /app/public/css/ /www/public/css/

RUN apk add --no-cache composer
WORKDIR /www
RUN composer dump-autoload --optimize --no-dev

COPY --chown=www:www ./.env.deploy /www/.env

RUN php8 artisan key:generate

RUN php8 artisan optimize:clear
RUN php8 artisan optimize
RUN php8 artisan view:cache

# Install Supervisor Process Control System
RUN apk add --no-cache supervisor
COPY ./.docker/supervisord.conf /etc/supervisord.conf

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

RUN touch /var/log/php8/error.log
RUN chown www:www /var/log/php8/error.log
#RUN chmod 666 /var/log/php8/error.log

RUN touch /www/storage/logs/laravel.log
RUN chown www:www /www/storage/logs/laravel.log
#RUN chmod 666 /www/storage/logs/laravel.log

RUN rm -f /etc/nginx/http.d/default.conf

EXPOSE 8080
CMD /usr/bin/supervisord -c "/etc/supervisord.conf"
