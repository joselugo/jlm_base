#!/bin/sh
### BEGIN INIT INFO
# Provides:          scriptname1
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Start daemon at boot time
# Description:       Enable service provided by daemon.
### END INIT INFO
if uname -a | grep FreeBSD | grep -v grep
then
/bin/sh /usr/local/www/data-dist/html/sh/emisores.sh &
else
/usr/bin/sudo /usr/bin/gammu-smsd -c /etc/gammu-smsdrc &
/bin/sh /var/www/html/herramientas/sh/emisores.sh &
/bin/sh /var/www/html/herramientas/sh/ping_emisores.sh &
fi