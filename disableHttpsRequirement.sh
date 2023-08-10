#!/bin/sh
sed -i "s/\$this->app\['request']->server->set('HTTPS', true);/\/\/ /g" app/Providers/AppServiceProvider.php
rm "$0"
