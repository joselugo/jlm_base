#!/usr/bin/expect -f

set port        [lindex $argv 0]
set ip          [lindex $argv 1]
set user        [lindex $argv 2]
set pass        [lindex $argv 3]
set interface   [lindex $argv 4]
set puerto      [lindex $argv 5]
set idonu       [lindex $argv 6]

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

send -- "interface gpon $interface\n"

expect "MA5608T(config-if-gpon-$interface)#"

send -- "ont reset $puerto $idonu \n"

send -- "y \n"

expect "MA5608T(config-if-gpon-$interface)#"

send -- "quit\n"

expect "MA5608T(config)#"




#expect eof