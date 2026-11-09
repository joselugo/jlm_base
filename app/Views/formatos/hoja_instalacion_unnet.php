<html>
<head>
  <title>Hoja de instalacíon</title>
</head>
<body>
<style type="text/css">
body,td,th {
  font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;
  font-style: normal;
  font-size: 11px;
}
body {
margin-top: 20px;
}
.table { 
font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;
border-collapse: collapse;
width: 100%;
border: 1px solid #bdbdbd;
font-size: 11px;
}

.table th { 
    border: 1px solid #bdbdbd;
    padding: 4px;
}
.table td {
border: 1px solid #bdbdbd;
    padding: 2px;
  font-size: 11px;
}

.table th {
    padding-top: 6px;
    padding-bottom: 6px;
    text-align: left;
    background-image: -webkit-linear-gradient(top,#f5f5f5 0,#e8e8e8 100%);
    background-image: -o-linear-gradient(top,#f5f5f5 0,#e8e8e8 100%);
    background-image: -webkit-gradient(linear,left top,left bottom,from(#f5f5f5),to(#e8e8e8));
    background-image: linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff5f5f5', endColorstr='#ffe8e8e8', GradientType=0);
    background-repeat: repeat-x;
}
</style>
<div style="height:90%">

<table cellspacing="3" width="100%">
  <tbody><tr>
    <td valign="top" width="40%"><img alt="" src="<?=base_url()?>public/images/logo_unnet.png" style="max-width: 180px; height: auto;" />
    </td>
    <td valign="top" width="60%">
    <p><span style="font-size:14px;"><b>ÚNnet Telecomunicaciones</b></span></p>
    <p><span style="font-size:10px;"><b>Internet de verdad</b></span></p>
  

    <p>Tel: 999 394 3333</p>
    <p>Facebook/UNnetInternet</p>

    </td></tr>
  </tbody>
</table>

<h1 style="width:100%; text-align:center"><b>FORMATO DE INSTALACI&Oacute;N</b></h1>
<table class="table" width="100%">
  <thead>
    <tr>
    <th align="center" colspan="2" valign="middle"><strong>Datos del usuario</strong></th>
    </tr>
  </thead>
  <tbody>
   <tr><td align="right" width="24%"><b>Folio usuario:</b></td><td width="76%"><?=$id?></td></tr>
    <tr><td align="right" width="24%"><b>Nombre y Apellidos:</b></td><td width="76%"><?=$nombre?></td></tr>
 
    <tr><td align="right"><b>N&ordm; Tel&eacute;fono m&oacute;vil: </b></td><td><?=$movil?></td></tr><tr><td align="right"><b>Direcci&oacute;n: </b></td><td><?=$direccion?></td></tr>
     <tr><td align="right"><b>Contraseña WiFi:</b></td><td><?=$password?></td></tr>
  </tbody>
</table>

<h4><b>SERVICIOS CONTRATADOS</b></h4>

<table class="table" width="100%">
  <thead><tr>
    <th align="center"><b>Plan</b></th>
    <th align="center"><b>Precio</b></th>
    <th align="center"><b>Velocidad</b></th>
    <th align="center"><b>Usuario</b></th>
    </tr>
  </thead>
  <tbody>

    <?php 
 foreach($servicios as $item) {
   echo "<tr>";

   echo "<td>".$item->plan."</td>";
   echo "<td>$ ".$item->costo."</td>";
   echo "<td>".$item->velocidad."</td>";
   echo "<td></td>";
   echo "</tr>";
 }

 ?> 
  </tbody>
</table>

<h4><b>EQUIPOS INSTALADOS</b></h4>

<table class="table" width="100%">
  <thead><tr>
    <th align="center"><b>Equipo</b></th>
    <th align="center"><b>Descripci&oacute;n</b></th>
    <th align="center"><b>N&ordm; Mac</b></th>
    <th align="center"><b>N&ordm; Serie</b></th>
    </tr>
  </thead>
  <tbody>
        <?php 
 foreach($materiales as $item) {
   echo "<tr>";

   echo "<td>".$item->tipo."</td>";
   echo "<td>".$item->producto."</td>";
   echo "<td>".$item->mac."</td>";
   echo "<td>".$item->serial."</td>";
   echo "</tr>";
 }

 ?>
    </tr>
  </tbody>
</table>

<h4><b>ACCESORIOS INSTALADOS</b></h4>

<table class="table">
  <thead><tr>
    <th align="center"><b>Materiales</b></th>
    <th align="center"><b>Cantidad</b></th>
    </tr>
  </thead>
  <tbody>
            <?php 
 foreach($accesorios as $item) {
   echo "<tr>";
   echo "<td>".$item->producto."</td>";
   echo "<td style='text-align: center;'>".$item->cantidad." ".$item->medida."</td>";
   echo "</tr>";
 }

 ?>
  </tbody>
</table>

<table class="table" style="margin-top:20px" width="100%">
  <thead><tr>
    <th align="left">
    <h4><b>Observaciones</b></h4>
    </th>
    </tr>
  </thead>
  <tbody><tr><td height="120" valign="top">

TELÉFONO ATENCIÓN A CLIENTES/CONTRATACIÓN: 999 394 3333. <br>

FACTURACIÓN: 999 394 3333 - HORARIOS: De Lunes a Viernes de 8:00 A.M. a 7:00 P.M. Sábados No se labora y
Domingo de 9:00 A.M. a 1:00 P.M.<br><br>

TELÉFONO SOPORTE/MANTENIMIENTO: 999 394 3333 - Envié mediante WhatsApp la descripción de su problema y su Nombre Completo al número de soporte para una mejor revisión de su servicio. (Si su reporte se registra después de las 9:00 PM su problema será atendido
al día siguiente.) <br><br>


- Los equipos y material utilizado en la instalación se encuentran en arrendamiento y son propiedad de ÚNnet.<br> 
- Encaso de que los equipos sufran un daño por una descarga eléctrica, y deba ser sustituido no podrá hacerse valida la garantía del
equipo. <br><br>
- En caso de que los equipos tengan reparación se realizara el cobro proporcional de la reparación efectuada.<br><br><br><br><br>

___________________________<br>
FIRMA DE CONFORMIDAD

  </td></tr>
  </tbody>
</table>
</div>

<div style="width:100%; height:7%; margin-top: 40px">
<table width="100%">
  <tbody><tr><td align="center">.................................................................</td><td align="center">.................................................................</td></tr><tr><td align="center" style="font-style: normal; font-weight: bold;">
    <p><?=$nombre?></p>

    <p>Cliente</p>
    </td><td align="center" style="font-style: normal; font-weight: bold;">
    <p></p>

    <p>T&eacute;cnico</p>
    </td></tr>
  </tbody>
</table>
</div>
</body>
</html>
