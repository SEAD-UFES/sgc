FROM nginx:alpine

RUN apk update && apk upgrade --no-cache

#Infra dependencies
RUN apk add --no-cache php8 php8-fpm 

#Laravel site declared dependencies
RUN apk add --no-cache php8-bcmath php8-ctype php8-fileinfo php8-json php8-mbstring php8-openssl php8-pdo php8-tokenizer php8-xml php8-gd php8-intl php8-mbstring php8-pdo_mysql

#Dependencies that brake the build if not installed
RUN apk add --no-cache php8-curl php8-phar php8-dom php8-simplexml php8-xmlwriter php8-xmlreader php8-zip php8-session php8-iconv

RUN ln -s /usr/bin/php8 /usr/bin/php


COPY .docker/php.ini /etc/php8/php.ini
COPY .docker/default.conf /etc/nginx/conf.d/default.conf
COPY .docker/nginx.conf /etc/nginx/nginx.conf

RUN rm -rf /usr/share/nginx/html/*

RUN apk add --no-cache supervisor
COPY .docker/supervisord.conf /etc/supervisord.conf
CMD /usr/bin/supervisord -c "/etc/supervisord.conf"

#####
USER nginx
WORKDIR /home/nginx/
# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

#####
USER root
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

RUN apk add --no-cache npm

#####
USER nginx

COPY --chown=nginx:nginx . /usr/share/nginx/html/

WORKDIR /usr/share/nginx/html

#####
USER root
RUN  rm -rf ./.docker
RUN mkdir /usr/share/nginx/html/node_modules
RUN chown -R nginx:nginx node_modules
RUN mkdir /var/cache/nginx/.npm
RUN chown -R nginx:nginx /var/cache/nginx/.npm

RUN touch /usr/share/nginx/html/storage/logs/laravel.log
RUN chmod -R 777 /usr/share/nginx/html/storage/logs/laravel.log
RUN chmod -R 777 /usr/share/nginx/html/storage/framework/sessions

#####
USER nginx
RUN composer install --optimize-autoloader --no-dev
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

RUN npm ci --production
RUN npm i cross-env
RUN npm run prod

#####
USER root

EXPOSE 80
