<html>
<head>
  <title>Carta de invitación</title>
</head>
<body>
  <div id="marca-agua"><img alt="" src="public/images/logo-netium-agua.png"/ style="width: 660px; "></div>
<table border="0" cellpadding="1" cellspacing="1" style="width:100%;">
  <tbody>
    <tr>
      <td width="70%">
      <p>&nbsp;</p>

      <p>&nbsp;</p>

      <p>&nbsp;</p>

      <p><span style="font-size:14px;"><span style="font-family:verdana,geneva,sans-serif;">Umán, Yucatán México a <?=date("d")." de ".$mes." de ".date("Y")?> </span></span></p>
      </td>
      <td><img alt="" src="public/images/logo-netium.png" style="width: 170px; height: 128px; float: right;" /></td>
    </tr>
  </tbody>
</table>

<p align="center"><span style="font-family:verdana,geneva,sans-serif;"><strong>CARTA DE NOTIFICACIÓN DE ADEUDO</strong></span></p>
<p></p>
<p>ESTIMADO CLIENTE: <strong><?=$cliente?></strong></p>


<p>ASUNTO: <strong>Invitaci&oacute;n para ponerse al d&iacute;a con el servicio Netium.</strong></p>

<p>&nbsp;</p>

<p style="font-size: 13">Por medio de la presente y de manera respetuosa nos dirigimos a usted para hacerle una invitaci&oacute;n para acudir a nuestras oficinas y <strong>PONERSE AL D&Iacute;A CON LOS PAGOS CORRESPONDIENTES A LOS SERVICIOS NETIUM</strong>, dado que en nuestro sistema su cuenta presenta un saldo vencido por 
 
<strong>$ <?=number_format($total,2)?></strong>

<u>(<?=$en_letras?>)</u></p>
<table width="100%">
  <tr>
      <th style="text-align:left">Folio</th> <th style="text-align:left">Emitido</th> <th style="text-align:left">Vencimiento</th> <th style="text-align:left">Subtotal</th> <th style="text-align:left">Impuesto</th> <th style="text-align:left">Total</th>
  </tr>
 
  <?php 

 foreach ($facturas as $item) {
   echo "<tr>";
   echo "<td>".$item->id."</td>";
   echo "<td>".$item->emitido."</td>";
   echo "<td>".$item->vencimiento."</td>";
   echo "<td>".$item->sub_total."</td>";
   echo "<td>".$item->iva_igv."</td>";
   echo "<td>".$item->total."</td>";
   echo "</tr>";
 }

 ?>
</table>

<p>Cabe recalcar que la empresa Netium Comunicaciones tiene como principal objetivo el que usted siga gozando de nuestro servicio y estamos en la total disposici&oacute;n de llegar a un acuerdo con usted para poder estar al d&iacute;a y realizar la reconexi&oacute;n.</p>

<p>Hoy d&iacute;a, el internet es una herramienta &uacute;til y necesaria tanto para la vida cotidiana como para el desarrollo educativo de los j&oacute;venes, estamos seguros que la empresa Netium Comunicaciones juntamente con usted podemos encontrar una soluci&oacute;n inmediata.</p>

<p>Ponemos a su disposici&oacute;n el <strong>999 159 7609, 800 777 7000</strong> y le recordamos que los horarios de atenci&oacute;n son de Lunes a Jueves de 8:00 a 19:30 hrs. Viernes de 8:00 a 17:00 hrs. Y Domingos de 9:00 a 13:00 hrs.</p>

<p>Nuestra direcci&oacute;n es Calle 24 x 21 esquina. Plaza 21, local 1. Contra esquina del Templo de la Cruz.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p align="center">Atte: NETIUM COMUNICACIONES</p>

<p align="center">DEPARTAMENTO DE COBRANZA</p>

<p>&nbsp;</p>

<p>&nbsp;</p>


<p style="font-size:11px">Nota: Le invitamos a ponerse en contacto con nosotros en un plazo no mayor a 72 horas h&aacute;biles para llegar a un acuerdo oportuno. Evite que adoptemos cualquier acci&oacute;n legal y comercial que consideremos oportuna para la cancelaci&oacute;n del servicio y la recuperaci&oacute;n de los equipos de red instalados en su domicilio.</p>
<style type="text/css">

@page{
  margin-left: 50px;
  margin-top: 20px;
}

#marca-agua{
  position: absolute;
  margin-top: 320px;
  margin-left: 30px;
  z-index: -1;
  filter: grayscale(0%);
}

.tabladatoemisor{
font-size:16px; 
}

.emisor{
font-size:23px;
}
.letracode{
font-size:16px;
font-weight:bold;
font-family: Arial;

}

.letrafc{
  width:65px;
  height:65px;
  font-family: Arial;
  border:1px solid #000000;
  text-align:center;
  vertical-align:middle;
  left: 0; 
  right: 0; 
  margin-left: auto; 
  margin-right: auto;
  top:16px;
  position:absolute;
  background:#FFFFFF;
}
.letratipo{
  font-family: Arial;
  font-size:45px;
  font-family: Arial;
  font-weight:bold;
}


.tablaiva td{
border:1px solid #000000;
border-radius:4px;
background:#e6e6e6; 
}

.tablaitems th{
background:#d0d0d0;
}

.tablaitems {
    border-collapse: collapse;
}

.tablaitems th {
border-bottom:1px solid #000000;
border-left:1px solid #000000;
}
.tablaitems th:first-child{
border-left:none;
}
.tablaitems td{
  padding:8px 5px;
  font-size:11px;

}

.dvitem{
height: 540px;
border:1px solid #000000;
margin-top:5px;
}


.tablacliente{
  border:1px solid #000000;
  margin-top:5px;
}

.tablacliente td{
padding:5px 10px;
}

.tablahead{
  border:1px solid #000000;
  margin-top:5px;
}

.tabladataemisor td{
  padding: 1px 8px;

}


table{
font-size: 12px;
font-family: Arial;
}

.mediano{
  font-size:13px;
}
</style>
</p>
</body>
</html>
