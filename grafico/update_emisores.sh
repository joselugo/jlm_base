#!/bin/bash
#
### importar requerimientos
command="/bin/ping -q -n -c 3"
gawk="/usr/bin/gawk"
rrdtool="/usr/bin/rrdtool"

### Movernos al directorio del script (grafico/)
cd "$(dirname "$0")" || exit 1

### Credenciales desde el .env de la raíz (mismo archivo que CI4 y los crons)
ENV_FILE="../.env"
env_valor() {
    local clave="${1//./\\.}"
    sed -n "s/^[[:space:]]*${clave}[[:space:]]*=[[:space:]]*//p" "$ENV_FILE" | tail -n 1 | tr -d '\r' \
        | sed -e 's/[[:space:]]*$//' -e "s/^[\"']\(.*\)[\"']$/\1/"
}
host=$(env_valor database.default.hostname)
port=$(env_valor database.default.port)
user=$(env_valor database.default.username)
database=$(env_valor database.default.database)
# La contraseña va por MYSQL_PWD para que no quede visible en ps
export MYSQL_PWD="$(env_valor database.default.password)"
### @return los datos del comando ping
get_data() {
    local output=$($command $1 2>&1)
    local method=$(echo "$output" | $gawk '
        BEGIN {pl=100; rtt=0.1}
        /packets transmitted/ {
            match($0, /([0-9]+)% packet loss/, datapl)
            pl=datapl[1]
        }
        /min\/avg\/max/ {
            match($4, /(.*)\/(.*)\/(.*)\/(.*)/, datartt)
            rtt=datartt[2]
        }
        END {print pl ":" rtt}
        ')
    RETURN_DATA=$method
}
### Actualizar en las DB.
insert_data(){
    get_data $1
    $rrdtool update ping_db_id_$2.rrd --template pl:rtt N:$RETURN_DATA
}

### Recorre los emisores de la DB.
while IFS=$'\t' read ip id ;do
        if [ "$ip"!=="ip" ]; then
            insert_data $ip $id &
        fi
done  < <(mysql -N -h "${host:-localhost}" -P "${port:-3306}" -u "$user" -D "$database" -e 'SELECT ip,id FROM emisores')
