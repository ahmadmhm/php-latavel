#!/bin/sh
echo "Composer dump-autoload"
composer dump-autoload
echo "App install was successful"

/usr/bin/supervisord  -c "/etc/supervisor/conf.d/supervisord.conf"
