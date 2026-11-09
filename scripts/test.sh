#!/usr/bin/expect -f

set action   [lindex $argv 0]
set port     [lindex $argv 1]
set ip       [lindex $argv 2]
set user     [lindex $argv 3]
set pass     [lindex $argv 4]
set prompt   [lindex $argv 5]
set timeout 2

spawn telnet "$ip"

expect "User name"

send -- "$user\n"

expect "User password"

send -- "$pass\n"

expect "$prompt>"

send -- "enable\n"

expect "$prompt#"


#expect eof