
#!/usr/bin/bash
#
## change directory to the rrdtool script dir
cd /var/www/html/app/grafico

## Graph for last 24 hours
/usr/bin/rrdtool graph latency_graph.png \
-w 600 -h 310 -a PNG \
--slope-mode \
--start $1 \
--font DEFAULT:9: \
--title "$3" \
--watermark "Generado por Netium" \
--vertical-label "Ping (ms)" \
--right-axis-label "Ping (ms)" \
--lower-limit 0 \
--right-axis 1:0 \
--x-grid $4 \
--alt-y-grid --rigid \
DEF:roundtrip=$2:rtt:MAX \
DEF:packetloss=$2:pl:MAX \
CDEF:PLNone=packetloss,0,0,LIMIT,UN,UNKN,INF,IF \
CDEF:PL10=packetloss,1,10,LIMIT,UN,UNKN,INF,IF \
CDEF:PL25=packetloss,10,25,LIMIT,UN,UNKN,INF,IF \
CDEF:PL50=packetloss,25,50,LIMIT,UN,UNKN,INF,IF \
CDEF:PL100=packetloss,50,100,LIMIT,UN,UNKN,INF,IF \
AREA:roundtrip#9DFFFF:"ms" \
LINE0.8:roundtrip#0000FF \
GPRINT:roundtrip:AVERAGE:"Minimo\: %5.2lf ms" \
GPRINT:roundtrip:MIN:"Promedio\: %5.2lf ms" \
GPRINT:roundtrip:MAX:"Maximo\: %5.2lf ms" \
## copy to the web directory
cp latency_graph.png /var/www/html/app/public/images/graficas
