#!/usr/bin/expect -f

set ip   [lindex $argv 0]
set user     [lindex $argv 1]
set pass       [lindex $argv 2]
set onecommand     [lindex $argv 3]
set timeout 2


  spawn telnet "$ip"
    expect "User name"
    send -- "$user\n"
    expect "User password"
    send -- "$pass\n"
    expect ">"
    send -- "enable\n"
    expect "#"
    send -- "config\n"
    expect "config"
    send -- "$onecommand\n"
    expect "#"
    send -- "quit\n"
    expect "#"
    send -- "quit\n"
    expect "y/n"
    send -- "y\n"






#expect eof