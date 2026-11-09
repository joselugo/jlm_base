#!/usr/bin/expect -f

set ip   [lindex $argv 0]
set user     [lindex $argv 1]
set pass       [lindex $argv 2]
set timeout 2

spawn telnet "$ip"

expect "User name"

send -- "$user\n"

expect "User password"

send -- "$pass\n"

expect ">"

send -- "display version\n"

expect "{ <cr>|backplane<K>|frameid/slotid<S><Length 1-15> }"

send -- "\n"

expect " Press 'Q' to break"

send -- "q"

expect ">"

send -- "quit\n"

expect "Are you sure to log out? (y/n)"

send -- "y\n"
#expect eof