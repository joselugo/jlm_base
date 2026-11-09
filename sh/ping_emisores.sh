#!/bin/sh
if uname -a | grep FreeBSD | grep -v grep
then
PHP_EXECUTABLE="/usr/local/bin/php"
SCRIPT_DIRECTORY="/usr/local/www/data-dist/html/herramientas/cron/emisores.php"
else
PHP_EXECUTABLE="/usr/bin/php"
SCRIPT_DIRECTORY="/var/www/html/herramientas/cron/ping_emisores.php"
fi
while true
do
$PHP_EXECUTABLE $SCRIPT_DIRECTORY > /dev/null 2>&1
sleep 1
done
fi