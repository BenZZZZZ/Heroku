#!/bin/sh
cd /var/www/api
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install
