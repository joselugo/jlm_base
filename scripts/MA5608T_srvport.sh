#!/usr/bin/expect -f

set port        [lindex $argv 0]
set ip          [lindex $argv 1]
set user        [lindex $argv 2]
set pass        [lindex $argv 3]
set srvport     [lindex $argv 4]
set interface   [lindex $argv 5]
set onuid       [lindex $argv 6]
set trafic      [lindex $argv 7]
set vlan        [lindex $argv 8]
 
set timeout 2

spawn telnet "$ip"

expect "User name"

send -- "$user\n"

expect "User password"

send -- "$pass\n"

expect "MA5608T>"

send -- "enable\n"

expect "MA5608T#"

send -- "config\n"

expect "MA5608T(config)#"

send "service-port $srvport vlan $vlan gpon $interface ont $onuid gemport 10 multi-service user-vlan $vlan tag-transform translate inbound traffic-table index $trafic outbound traffic-table index $trafic \n"

expect "MA5608T(config)#"


#expect eof