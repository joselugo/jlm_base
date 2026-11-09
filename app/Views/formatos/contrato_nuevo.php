
<!doctype html>
<html>
<head><meta charset="UTF-8">
  <title>::-::-:: CONTRATO DE SERVICIOS ::-::-::</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link href="<?=base_url();?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?=base_url();?>css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<style>
.linea{
  text-decoration: underline;
  
}
</style>
<!-- <pre><?php print_r($datos)?></pre>
<pre><?php print_r($f_sucursal)?></pre> -->
<table  width="100%" style="text-align:center; color:grey;">
  <tr>
  <td width="20%"><img src="<?= base_url()?>public/images/logos_sucursales/logo_factura_sucursal_<?= $f_sucursal->sucursal?>.jpg" style="width:100"></td>
   <td width="60%"><strong style="font-size:13px;font-family: Arial Black;font-weight: bold;">CARÁTULA DEL CONTRATO DE SERVICIOS</strong><br>
        <strong style="font-size:9px;font-family: Arial Black;">
          <?= $f_sucursal->alias?><br>
          <?= $f_sucursal->direccion?>
          <br>
          <?= $f_sucursal->rfc?><br>
          TEL: <?= $f_sucursal->telefono?><br>
        </strong> 
   </td>
    <td width="20%">
      <table with="100%" class="tbl-fecha">
        <tr>
          <td>DIA</td><td>MES</td><td>AÑO</td>
        </tr>
          <tr>
          <td class="td-fecha"><?= $datos->f_dia?></td><td class="td-fecha"><?= $datos->f_mes?></td><td class="td-fecha"><?= $datos->f_anio?></td>
        </tr>
      </table>
       <table border="1" with="100%" class="tbl-folio">
        <tr>
          <td class="td-folio">FOLIO</td>
        </tr>
          <tr>
          <td><?= $datos->idservicio?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<div style="border-radius:5px;margin:15px; margin-bottom:8px; border: 1px solid #e75115; padding:3px">
 <table width="100%" border="0" style="text-align:left; color:grey; font-size:11; margin-bottom:5px">
   <tr>
     <td style="font-family:Arial Black;font-weight: bold;" width="25%"><strong>DATOS DEL SUSCRIPTOR</strong></td><td width="25%"><?= $datos->persona==1?'<span style="width:100%" class="linea">(x)</span>':'___'?>  Persona física</td> <td width="25%"><?= $datos->persona==2?'<span style="width:100%" class="linea">(x)</span>':'___'?> Persona moral</td> <td width="25%">RFC: <?= $datos->rfc ?($datos->rfc==""?'__________________________':'<span style="width:100%" class="linea">'.$datos->rfc.'</span>'):'__________________________'?></td>
   </tr>
   <tr >
     <td style="padding-top:5px" colspan="4">Nombre / Razón Social: <span style="width:100%" class="linea"><?= $datos->nombre?></span> </td>
   </tr>
    <tr >
     <td style="padding-top:5px" colspan="1">Telefono: <?= $datos->telefono?'<span style="width:100%" class="linea">'.$datos->telefono.'</span>':'______________________'?> </td><td style="padding-top:5px" colspan="1">Celular: <?= $datos->movil?'<span style="width:100%" class="linea">'.$datos->movil.'</span>': '________________________'?></td><td style="padding-top:5px" colspan="2">Mail: <?= $datos->correo?'<span style="width:100%" class="linea">'.$datos->correo.'</span>':'________________________________________________________'?></td>
   </tr>
    <tr >
     <td style="padding-top:5px" colspan="4">Representante legal: _______________________________________________________________________________________________________ </td>
   </tr>
 </table>
</div>

<div style="border-radius:5px;margin:15px; margin-top:0px; margin-bottom:8px; border: 1px solid #e75115; padding:3px">
 <table width="100%" border="0" style="text-align:left; color:grey; font-size:11; margin-bottom:5px">
   <tr>
     <td style="font-family:Arial Black;font-weight: bold;" width="25%"><strong>DOMICILIO FISCAL</strong></td><td width="25%">Calle: ________________________</td> <td width="25%">N° Ext/Int:____________________</td> <td width="25%">Cruzamientos: __________________</td>
   </tr>
    <tr>
     <td style="padding-top:5px" width="25%">Colonia: _____________________</td><td style="padding-top:5px" width="25%">Municipio: ______________________</td> <td style="padding-top:5px" width="25%">Estado: _____________________</td> <td style="padding-top:5px" width="25%">CP: ___________________________</td>
   </tr>
     <tr>
     <td style="font-family:Arial Black;font-weight: bold; padding-top:10px" width="25%"><strong>DOMICILIO DE INSTALACIÓN</strong></td><td width="25%" colspan="3" style="padding-top:10px">___  Mismo que el domicilio fiscal</td>
   </tr>
    <tr>
     <td width="25%" colspan="4" style="padding-top:5px">Domicilio: <?= $datos->direccion?'<span style="width:100%" class="linea">'.$datos->direccion.'</span>':'_________________________________________________________________________________________________________________'?></td>
   </tr>
    <tr >
     <td style="padding-top:5px" colspan="2">Teléfono contacto: ______________________________________________ </td><td style="padding-top:5px" colspan="2">Celular contacto: ____________________________________________</td>
   </tr>  
     <tr >
       <td style="padding-top:5px" colspan="4">Referencias del domicilio: _____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</td>
   </tr>
 
 </table>
</div>
 
        <div style="border-radius:5px; margin-left:15px; border: 1px solid #e75115; width:49%;float:left ;text-align:left; color:grey; font-size:11; margin-bottom:5px; padding:3px">
         <strong style="font-family:Arial Black;font-weight: bold;">PLAN CONTRATADO </strong><br><br>
Nombre: <?= $datos->plan?'<span style="width:100%" class="linea">'.$datos->plan.'</span>':'_____________________________________________'?><br>
Precio: <?= $datos->costo?'<span style="width:100%" class="linea"> $'.$datos->costo.'</span>':'_____________________________________________'?><br>
Suscripción (Único): ________________________________<br>
Costo de conexión (Único): __________________________<br>
Otros servicios: ____________________________________<br>
Precio otros servicios: _____________________________<br>
<br>
TOTAL A PAGAR MENSUAL _____________________________<br><br>
____ Plazo De Contratación ______ Meses<br>
____ Sin Plazo De Contratación<br><br>
<p style="font-size:9px; text-align:justify">Otros cargos adicionales como COSTO DE RECONEXIÓN o MORA DE VENCIMIENTO, son generados automáticamente en la factura del cliente al no cumplir con las obligaciones de fecha de pago acordado con el cliente, así como gastos administrativos, referentes a la cancelación de servicio por incumplimiento de entrega del equipo terminal instalado en el domicilio establecido en esta caratula . 
En caso de que el contrato sea a un plazo de contratación establecida, y el cliente quiera cancelar deberá pagar la parte proporcional por cancelación anticipada.</p>



        </div>

        <div style="border-radius:5px; border: 1px solid #e75115; width:44.3%;float:right; margin-right:15px; color:grey; margin-bottom:5px; padding:3px">
       <p style="font-size:9px; text-align:justify">En caso de cancelación y no devolución del equipo, por este pagaré me obligo incondicionalmente a pagar a la orden COMUNICACIONES DIGITALES NETIUM SAS DE CV. y/o a quien legalmente la represente, la cantidad de $___________ (EN LETRAS) valor del equipo instalado para los servicios contratados en el presente. En caso de que el CLIENTE no cubra la totalidad de la cantidad establecida en este pagaré, COMUNICACIONES DIGITALES NETIUM SAS DE CV podrá exigir el pago adicional de intereses moratorios sobre saldos insolutos de la suma principal más una tasa de interés moratorio equivalente al TIIE + 5PP + IVA.</p>

     
                                    <p style="text-align:center">_____________________________________________</p>
      <p style="text-align:center; font-size:10px">Nombre y firma del suscriptor/ Representante legal</p>

        </div>
         
          <div style="border-radius:5px; border: 1px solid #e75115; width:44.3%;float:right; margin-right:15px; color:grey; margin-bottom: 0px; padding:3px; padding-bottom:-3px">
      <strong style="font-size: 10px">ENVIAR ESTADO DEL CUENTA AL CORREO ELECTRÓNICO</strong>
      <p style="text-align:justify; font-size:9px;padding-bottom:4px">Acepto recibir mi estado de cuenta, avisos e información relacionada con mi servicio en la dirección de correo electrónico mencionada. Si el cliente no recibe su estado de cuenta, podrá consultarlo en www.netium.com.mx, o en la línea 01 8000 777 7000, 999 1597609 ya que el hecho de que no lo reciba no lo exime del pago.</p>
      <p style="text-align:center;">_____________________________________________</p>

        </div>

       <div style="border-radius:5px; border: 1px solid #e75115; margin:15px; margin-top:8px; color:grey; padding:3px">
         <p style="font-size:10px; text-align:justify;">ACEPTO RECIBIR PUBLICIDAD SOBRE PRODUCTOS O SERVICIOS, PARA QUE NETIUM UTILICE MI INFORMACIÓN CON FINES DE MERCADOTECNICOS</p>
       <br> 
        </div>


       <div style="border-radius:5px; border: 1px solid #e75115;margin:15px; margin-top:8px; color:grey;padding:3px">
       <p style="font-size:10px; text-align:justify;">ACEPTO RECIBIR PUBLICIDAD SOBRE PRODUCTOS O SERVICIOS, PARA QUE NETIUM UTILICE MI INFORMACIÓN CON FINES DE MERCADOTECNICOS</p>
       <br> 
        </div>
        <p style="font-size:10px; text-align:justify; padding:15px;padding-top:-5px;color:grey;">El cliente en este acto manifiesta para todos los efectos legales a que haya lugar, que los datos indicados por él en esta carátula son correctos y ciertos y que al firmar el presente      instrumento, está de acuerdo en sujetarse a las cláusulas del CONTRATO DE PRESTACION DE SERVICIOS DE TELECOMUNICACIONES que se establece juntamente con esta Carátula.</p>
     


      <div style="width:45%;float:left; margin-left:15px; color:grey; padding:3px; padding-top:0px">
       <p style="text-align:center">_____________________________________________</p>
       <p style="text-align:center; font-size:10px">NOMBRE Y FIRMA DEL SUSCRIPTOR / REPRESENTANTE LEGAL</p>
     </div>
      <div style="width:45%;float:right; margin-right:15px; color:grey; padding:3px; padding-top:0px">
       <p style="text-align:center">_____________________________________________</p>
       <p style="text-align:center; font-size:10px">NOMBRE Y FIRMA DEL REPRESENTANTE LEGAL DE NETIUM</p>
     </div>
     <p width="100%" style="text-align:center; color:grey; font-size:8px">COMUNICACIONES DIGITALES NETIUM S.A.S DE C.V. TODOS LOS DERECHOS RESERVADOS. <br>
TARIFAS REGISTRADAS ANTE IFT. PLANES, PRECIOS Y TARIFAS YA INCLUYEN IMPUESTOS APLICABLES Y ESTÁN SUJETOS A CAMBIOS CON NOTIFICACIÓN ANTICIPADA AL CLIENTE 
</p>

  <p width="100%" style="text-align:right; color:grey; font-size:9px; padding-right:15px">Fecha de impresión del documento: 20 de marzo de 2019 a las 6:00 pm</p>
  <br><br><br>
  <div width="100%" style="padding:20px">
    
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">CONTRATO&nbsp; DE&nbsp; PRESTACI&Oacute;N &nbsp;DE&nbsp;&nbsp; SERVICIO&nbsp;&nbsp; DE&nbsp; <strong>INTERNET</strong> QUE&nbsp;&nbsp; CELEBRAN&nbsp;&nbsp; POR&nbsp;&nbsp; UNA PARTE <strong>COMUNICACIONES DIGITALES NETIUM S.A.S. DE C.V.</strong> A&nbsp; QUIEN&nbsp; EN LO SUCESIVO SE LE&nbsp; DENOMINAR&Aacute; <strong>&ldquo;NETIUM&rdquo;</strong> Y&nbsp; POR OTRA PARTE ALA PERSONA CUYO NOMBRE Y DIRRECCION QUEDAN ASENTADOS EN LA CAR&Aacute;TULA DEL PRESENTE DOCUMENTO A QUIEN EN LO SUCESIVO SE LE DENOMINAR&Aacute; <strong>&ldquo;CLIENTE&rdquo;</strong>, BAJO LAS SIGUIENTES:</span></p>
<p style="margin-bottom: .0001pt; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">D E C L A R A C I O N E S</span></strong></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">I.-</span></strong><span style="font-family: Arial, sans-serif;"> Declara <strong>&ldquo;NETIUM&rdquo;</strong></span></span></p>
<ol>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">A)</span></strong><span style="font-family: Arial, sans-serif;"> Ser una Persona Moral con capacidad para celebrar el presente contrato, de acuerdo al Acta Constitutiva No. ____ de fecha ___ de _________ de 2019, presentada ante el Licenciado _________________________________, Notario P&uacute;blico n&uacute;mero 26 del Estado de Yucat&aacute;n.</span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">B)</span></strong><span style="font-family: Arial, sans-serif;"> Su representante legal cuenta con poderes espec&iacute;ficos de conciliaci&oacute;n para la celebraci&oacute;n de este Contrato.</span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">C</span></strong><span style="font-family: Arial, sans-serif;">) Objeto Social: <strong>COMUNICACIONES DIGITALES NETIUM S.A.S. DE C.V.</strong> para la prestaci&oacute;n de servicios de telecomunicaciones. </span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">D)</span></strong><span style="font-family: Arial, sans-serif;"> Con Registro Federal de Contribuyente CDN180829D49.</span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">E)</span></strong><span style="font-family: Arial, sans-serif;"> Con direcci&oacute;n fiscal en la Calle 21 No. 125 exterior, cruzamientos 24 y 22 Col. Centro, Um&aacute;n, C.P. 97390 en el Estado de Yucat&aacute;n.</span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">F)</span></strong><span style="font-family: Arial, sans-serif;"> Que es concesionario y/o autorizado y de la Red P&uacute;blica de Telecomunicaciones para prestar el servicio de INTERNET en Estado de Yucat&aacute;n. Acreditando lo anterior con el t&iacute;tulo de concesi&oacute;n y/o autorizaci&oacute;n de fecha 16 de noviembre de 2018, otorgado por parte del Instituto Federal en Telecomunicaciones, mismo que podr&aacute; ser constatado en la p&aacute;gina de Internet www.ift.org.mx</span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">G)</span></strong><span style="font-family: Arial, sans-serif;"> Se&ntilde;alo como domicilio legal el ubicado en Calle 21 No. 125 exterior, cruzamientos 24 y 22 Col. Centro, Um&aacute;n, C.P. 97390 en el Estado de Yucat&aacute;n. </span></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">II.-</span></strong><span style="font-family: Arial, sans-serif;"> Declara <strong>&ldquo;EL SUSCRIPTOR&rdquo;</strong></span></span></p>
<ol>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">A)</span></strong><span style="font-family: Arial, sans-serif;"> Que es una persona f&iacute;sica o moral cuyo nombre y direcci&oacute;n asentado en la car&aacute;tula son ciertos. </span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">B)</span></strong><span style="font-family: Arial, sans-serif;"> Que tiene pleno goce de sus derechos y capacidad legal para contratar y obligarse en t&eacute;rminos del presente contrato.</span></span></li>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">C) </span></strong><span style="font-family: Arial, sans-serif;">Que ha recibido informaci&oacute;n a su entera satisfacci&oacute;n respecto del contenido del presente contrato, y por lo tanto, ha comprendido cabalmente la forma en que NETIUM presta los servicios.</span></span></li>
</ol>
<p>&nbsp;</p>
<p style="margin-bottom: .0001pt; text-align: center; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">C L &Aacute; U S U L A S</span></strong></span></p>
<p style="margin-bottom: .0001pt; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">&nbsp;</span></strong></span></p>
<ol>
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> DEFINICIONES: </span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">ift.- </span></strong><span style="font-family: Arial, sans-serif;">Instituto Federal de Telecomunicaciones</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Car&aacute;tula.-</span></strong><span style="font-family: Arial, sans-serif;"> Documento(s) mediante&nbsp; el&nbsp;&nbsp; (los) cual&nbsp; (es) se &nbsp;especifica el &nbsp;tipo&nbsp; de Servicio &nbsp;y/o equipos &nbsp;que se le solicita al Proveedor por parte del Cliente para la prestaci&oacute;n de Servicios de&nbsp; Telecomunicaciones. En &eacute;sta el Cliente autoriza los cargos extras en caso de adquirir servicios adicionales contratados, mismos que se ver&aacute;n reflejados en el Comprobante Fiscal correspondiente.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Equipo terminal.-</span></strong> <span style="font-family: Arial, sans-serif;">El&nbsp; equipo y/o cableado y dem&aacute;s materiales propiedad&nbsp; del&nbsp; Proveedor, a trav&eacute;s del cual&nbsp; se prestar&aacute;&nbsp; el Servicio objeto&nbsp; del&nbsp; contrato&nbsp; y&nbsp; que&nbsp; se&nbsp;&nbsp; instalar&aacute;n&nbsp; en&nbsp;&nbsp; el&nbsp;&nbsp; interior&nbsp; y/o&nbsp; el&nbsp;&nbsp; exterior del&nbsp; inmueble&nbsp; del&nbsp; Cliente. </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Fecha de pago.- </span></strong><span style="font-family: Arial, sans-serif;">D&iacute;a de cada mes se&ntilde;alado por NETIUM, en el cual a m&aacute;s tardar el CLIENTE debe hacer el pago por los servicios del periodo de facturaci&oacute;n correspondiente. </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Fecha de suspensi&oacute;n.-</span></strong></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Servicios.- </span></strong><span style="font-family: Arial, sans-serif;">Son&nbsp; los &nbsp;servicios de&nbsp;&nbsp; Internet &nbsp;que proporcionan&nbsp; la&nbsp;&nbsp; capacidad de comunicaci&oacute;n de&nbsp;&nbsp;&nbsp; voz y/o datos entre usuarios,&nbsp; incluyendo&nbsp;&nbsp; la&nbsp;&nbsp; transmisi&oacute;n&nbsp; de&nbsp; se&ntilde;ales&nbsp;&nbsp; entre puntos &nbsp;&nbsp;terminales de&nbsp;&nbsp;&nbsp; conexi&oacute;n&nbsp;&nbsp; y&nbsp; opcionalmente &nbsp;&nbsp;los&nbsp; Servicios&nbsp; Adicionales&nbsp; que &nbsp;el&nbsp;&nbsp;&nbsp; Cliente contrate. </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Servicio adicionales.-</span></strong><span style="font-family: Arial, sans-serif;">Son &nbsp;los&nbsp; servicios&nbsp; que&nbsp; podr&aacute;n&nbsp; ser contratados&nbsp; por el&nbsp; Cliente distintos&nbsp; a&nbsp; los&nbsp; originalmente&nbsp; contratados&nbsp; de&nbsp;&nbsp;&nbsp; acuerdo&nbsp; a&nbsp; los&nbsp; planes&nbsp; comerciales, mismos&nbsp; que &nbsp;por facilidad&nbsp; para&nbsp; el&nbsp;&nbsp; Cliente, se&nbsp; describir&aacute;n&nbsp; y&nbsp; se&nbsp;&nbsp; pueden&nbsp; incluir&nbsp; al&nbsp;&nbsp; presente Contrato por medio de: (i) Car&aacute;tula, (ii) por v&iacute;a electr&oacute;nica a trav&eacute;s de la p&aacute;gina de internet de NETIUM.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">&nbsp;</span></strong></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Tarifas.- </span></strong><span style="font-family: Arial, sans-serif;">Es&nbsp; la&nbsp;&nbsp; cantidad&nbsp; que el&nbsp;&nbsp;&nbsp; Cliente&nbsp;&nbsp; deber&aacute;&nbsp; pagar: (1)&nbsp;&nbsp; Por el&nbsp;&nbsp; Servicio&nbsp;&nbsp; de&nbsp;&nbsp;&nbsp; conformidad&nbsp;&nbsp; con&nbsp;&nbsp; el&nbsp;&nbsp;&nbsp; Plan&nbsp;&nbsp; Comercial&nbsp;&nbsp; seleccionado, las Tarifas&nbsp; vigentes&nbsp; y&nbsp; previamente registradas ante&nbsp; el&nbsp;&nbsp; IFT&nbsp; sujetas&nbsp; al&nbsp;&nbsp; tipo&nbsp; de&nbsp;&nbsp; Plan&nbsp; Comercial</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Autorizaci&oacute;n.-</span></strong><span style="font-family: Arial, sans-serif;">La otorgada por el Instituto Federal de Telecomunicaciones y Radiodifusi&oacute;n mediante el oficio IFT/223/UCS/AUT-COM-251/2018.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">Profeco.- </span></strong><span style="font-family: Arial, sans-serif;">Procuradur&iacute;a Federal del Consumidor.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<ol start="2">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> OBJETO.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">Durante la vigencia del presente contrato, NETIUM se obliga a </span><span style="font-family: Arial, sans-serif;">proporcionar el servicio de Internet de acuerdo a los est&aacute;ndares m&iacute;nimos de calidad establecidos por el Instituto Federal de Telecomunicaciones, de forma continua, uniforme, regular y eficiente cumpliendo con las normas y metas de calidad aplicables, mediante el pago de la cuota de instalaci&oacute;n y de las mensualidades por parte del CLIENTE.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<ol start="3">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> VIGENCIA.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">E</span></strong><span style="font-family: Arial, sans-serif;">l presente contrato ser&aacute; por el plazo se&ntilde;alado en la Car&aacute;tula, contando a partir de la fecha de firma. Venciendo este plazo y si el CLIENTE no manifestara por escrito a NETIUM su intenci&oacute;n de dar por terminado el presente contrato, dentro de los 10 d&iacute;as siguientes a la fecha en que el mismo haya vencido, se renovar&aacute; autom&aacute;ticamente por tiempo indefinido.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">Si el CLIENTE no cumple con el plazo contratado, se obliga a pagar a NETIUM, por concepto de pena convencional por el incumplimiento, el equivalente al importe de las rentas mensuales restantes desde la fecha de incumplimiento hasta el vencimiento del plazo forzoso.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<ol start="4">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> OBLIGACIONES DE NETIUM.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">NETIUM</span><span style="font-family: Arial, sans-serif;"> est&aacute; obligado a tener en sus oficinas a disposici&oacute;n y la vista del p&uacute;blico con caracteres claramente legibles, las tarifas por el servicio vigentes, aprobadas e inscritas en el Registro de Tarifas del Instituto Federal de Telecomunicaciones, las cuales pueden ser consultadas en </span><a href="http://www.ift.org.mx"><span style="font-family: Arial, sans-serif; color: windowtext; text-decoration: none;">www.ift.org.mx</span></a></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&nbsp;</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">Las condiciones comerciales establecidas en este contrato, los planes, paquetes, las &aacute;reas o regiones geogr&aacute;ficas con cobertura que tiene autorizado NETIUM pueden ser consultados en la p&aacute;gina de Internet </span><a href="http://www.netium.com.mx"><span style="font-family: Arial, sans-serif;">www.netium.com.mx</span></a></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">&nbsp;</span></strong></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif; color: red;">&nbsp;</span></strong></span></p>
<ol start="5">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> OBLIGACIONES DEL CLIENTE. </span></strong><span style="font-family: arial, helvetica, sans-serif;">Pagar en las oficinas de NETIUM o en cualquier otro establecimiento autorizado por esta &uacute;ltima, los cargos que aparezcan en el estado de cuenta, que se facturan mensualmente, por los conceptos de renta b&aacute;sica, consumos, impuestos y cualquier otro servicio que NETIUM proporcione al CLIENTE o cualquier otro cargo por servicios prestados por terceros a trav&eacute;s de la interconexi&oacute;n de Datos, y EL CLIENTE haya otorgado autorizaci&oacute;n para que aparezcan en el estado de cuenta. Pagar a NETIUM los gastos de cobranza que se generen por su localizaci&oacute;n, en caso de no haber manifestado el cambio de domicilio y los derivados de las gestiones de abogados, cuando tengan adeudos pendientes por cubrir a NETIUM. Permitir el acceso a su domicilio al personal de NETIUM para realizar la instalaci&oacute;n y actividades necesarias para la prestaci&oacute;n del servicio contratado, as&iacute; como de abstenerse de realizar cambio alguno a las condiciones de instalaci&oacute;n con las que NETIUM entrego en operaci&oacute;n el servicio contratado.</span></span></li>
</ol>
<ol start="6">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> CAMBIO DE DOMICILIO. </span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">El CLIENTE podr&aacute; solicitar&nbsp; previamente y por&nbsp; los medios convenidos,&nbsp; que NETIUM preste el Servicio en otro domicilio diferente en donde se est&aacute; prestando actualmente, siempre y cuando, el CLIENTE est&eacute; al corriente con sus pagos; lo anterior estar&aacute; sujeto a que NETIUM&nbsp; cuente con las facilidades t&eacute;cnicas o de cobertura y no exista alguna restricci&oacute;n t&eacute;cnica, para efectuar el cambio solicitado. En este caso, el CLIENTE deber&aacute; pagar previo al cambio de domicilio la tarifa correspondiente registrada por&nbsp; el Proveedor ante la Autoridad competente.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;">&nbsp;</p>
<ol start="7">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> EQUIPO TERMINAL.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">NETIUM proveer&aacute; al CLIENTE, por s&iacute; mismo o a trav&eacute;s de un tercero con el que se tenga celebrado un convenio, el equipo terminal. Al momento de la instalaci&oacute;n del servicio. NETIUM entregar&aacute; al CLIENTE la posesi&oacute;n material del equipo de acceso, nombr&aacute;ndolo desde ahora, seg&uacute;n corresponda comodatario o arrendatario del equipo terminal.</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">Durante&nbsp; la vigencia del presente contrato, el CLIENTE es responsable del uso que le d&eacute; al equipo terminal, por lo que responder&aacute; por los da&ntilde;os, perjuicios, p&eacute;rdidas, robo o deterioro que sufra el mismo ya sea que fueren causados por el CLIENTE o por cualquier usuario del mismo o un tercero.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">En caso de que el CLIENTE incumpla con cualquier de sus obligaciones, o por la terminaci&oacute;n y/o rescisi&oacute;n del presente del presente contrato, el CLIENTE, sin necesidad de orden judicial o administrativa, deber&aacute; permitir al personal de NETIUM o del tercero con el que NETIUM tenga celebrado convenio, el retiro y la desinstalaci&oacute;n del equipo de terminal.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="8">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> GARANTIAS.</span></strong></span></li>
</ol>
<p style="text-align: justify; line-height: normal;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">deber&aacute; efectuar las instalaciones y empezar a prestar el servicio objeto del presente contrato, dentro de un plazo que no exceda de 10 d&iacute;as naturales a partir de la firma del contrato. </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">Una vez instalado el equipo terminal y </span><span style="font-family: Arial, sans-serif;">que EL CLIENTE cuente con el servicio NETIUM podr&aacute; comenzar a cobrarlo, dependiendo de la fecha de inicio de servicio, ser&aacute; total o parcialmente el cobro de la mensualidad, correspondiente &uacute;nicamente al periodo utilizado.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">EL CLIENTE contar&aacute; con los equipos personales m&iacute;nimos necesarios, con las caracter&iacute;sticas necesarias para hacer uso del Servicio de Internet,</span> <span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">&nbsp;informar&aacute; previo a la contrataci&oacute;n cu&aacute;les son los requerimientos m&iacute;nimos de los aparatos personales.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">&nbsp;</span><span style="font-family: Arial, sans-serif;">EL CLIENTE tomar&aacute; todas las medidas necesarias para proteger la informaci&oacute;n de su propiedad y/o software. </span><span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">no ser&aacute; responsable de da&ntilde;os que se causen por piratas inform&aacute;ticos y/o virus transmitidos a trav&eacute;s de Internet.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">no es responsable de la configuraci&oacute;n de dispositivos tales como <em>&ldquo;ruteadores&rdquo;, &ldquo;conmutador&rdquo;, &ldquo;hubs/concentrador&rdquo;, &ldquo;proxy&rdquo;</em>, que resulten necesarios para el uso concurrente del o de los Equipos Personales. EL CLIENTE </span><strong><span style="font-family: Arial, sans-serif;">&nbsp;</span></strong><span style="font-family: Arial, sans-serif;">deber&aacute; cubrir el costo tanto de las reparaciones del Equipo cuando le sea imputable la descompostura, mal uso, mal funcionamiento o fallas del mismo.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">&nbsp;</span><span style="font-family: Arial, sans-serif;">Por lo cual </span><span style="font-family: Arial, sans-serif;">se obliga a: No vender el Equipo, ni cambiar de domicilio la instalaci&oacute;n original, NO permitir que un tercero no autorizado haga uso del Equipo, ni ceder el Equipo sin el consentimiento de </span><span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">y utilizar el Equipo en la forma autorizada por</span> <span style="font-family: Arial, sans-serif;">la empresa.</span><span style="font-family: Arial, sans-serif;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">&nbsp;</span><span style="font-family: Arial, sans-serif;">EL CLIENTE </span><span style="font-family: Arial, sans-serif;">deber&aacute; contar con la instalaci&oacute;n el&eacute;ctrica que cumpla con las especificaciones de la Comisi&oacute;n Federal de Electricidad;&nbsp; </span><span style="font-family: Arial, sans-serif;">Tambi&eacute;n </span><span style="font-family: Arial, sans-serif;">reconoce que </span><span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">no se har&aacute; responsable de los da&ntilde;os o p&eacute;rdidas en los equipos propiedad del suscriptor por variaciones de voltaje o descargas el&eacute;ctricas, </span><span style="font-family: Arial, sans-serif;">EL CLIENTE</span><span style="font-family: Arial, sans-serif;"> y/o un tercero que haya manipulado las instalaciones el&eacute;ctricas.&nbsp; </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">En el caso de que el Equipo otorgado en comodato,&nbsp; sea robado u objeto de siniestro, </span><span style="font-family: Arial, sans-serif;">EL CLIENTE </span><span style="font-family: Arial, sans-serif;">deber&aacute; dar aviso inmediato al Centro de Atenci&oacute;n Telef&oacute;nica, en un plazo que no exceder&aacute; de veinticuatro horas posteriores al evento para la valoraci&oacute;n de los hechos y en su caso un acuerdo para la reposici&oacute;n del Equipo.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">EL CLIENTE </span><span style="font-family: Arial, sans-serif;">tendr&aacute; un plazo de 30 d&iacute;as h&aacute;biles posteriores al mismo para presentar copia certificada de la constancia correspondiente levantada ante una Autoridad Competente, para acreditar el objeto de robo o siniestro, para que no tenga costo la reposici&oacute;n del equipo.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="9">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> BONIFICACIONES DEL SERVICIO. </span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">EL CLIENTE </span><span style="font-family: Arial, sans-serif;">deber&aacute; comunicar en forma inmediata a NETIUM las fallas o interrupciones del Servicio(s).</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">Siempre y cuando por causas imputables a NETIUM no se preste el servicio de telecomunicaciones en la forma y t&eacute;rminos convenidos, &eacute;ste dejar&aacute; de cobrar al CLIENTE la parte proporcional del precio del servicio que se dej&oacute; de prestar, y deber&aacute; bonificar por lo menos el 20% del monto del periodo de afectaci&oacute;n. </span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">Cuando la suspensi&oacute;n sea por casos fortuitos o de fuerza mayor, si la misma dura m&aacute;s de 24 horas consecutivas siguientes al reporte NETIUM, har&aacute; la compensaci&oacute;n por la parte proporcional del periodo en que se dej&oacute; de prestar el servicio contratado por EL CLIENTE<strong>,</strong> dicha compensaci&oacute;n se ver&aacute; reflejada en el siguiente recibo y/o factura;</span> <span style="font-family: Arial, sans-serif;">adem&aacute;s deber&aacute; bonificar por lo menos el 20% del monto del periodo de afectaci&oacute;n. </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">A partir de que NETIUM reciba la llamada respecto a fallas y/o interrupciones en el servicio de Internet se proceder&aacute; a verificar el tipo de falla y en base a este se dictaminar&aacute; el tiempo para la reparaci&oacute;n la cual no puede exceder las 48 horas siguientes al reporte recibido.&nbsp; </span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">NETIUM dar&aacute; aviso a EL CLIENTE, por cualquier medio incluido el electr&oacute;nico y al Instituto Federal de Telecomunicaciones por escrito, de cualquier circunstancia previsible que repercuta en forma generalizada y significativa en la prestaci&oacute;n del servicio, con una antelaci&oacute;n m&iacute;nima de 24 horas. Dicha circunstancia no podr&aacute; afectar el servicio por m&aacute;s de 24 horas en cuyo caso NETIUM </span><span style="font-family: Arial, sans-serif;">dejar&aacute; de cobrar al CLIENTE la parte proporcional del precio del servicio que se dej&oacute; de prestar, y deber&aacute; bonificar por lo menos el&nbsp; 20% del monto del periodo de afectaci&oacute;n, Lo mismo ocurrir&aacute; si NETIUM no da aviso al </span><span style="font-family: Arial, sans-serif;">Instituto Federal de Telecomunicaciones conforme a la presente cl&aacute;usula.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">Trat&aacute;ndose de cargos o cobros indebidos realizados por </span><span style="font-family: Arial, sans-serif;">NETIUM, </span><span style="font-family: Arial, sans-serif;">los mismos deber&aacute;n ser reembolsados en un plazo no mayor a 2 ciclos de facturaci&oacute;n adem&aacute;s de bonificar al menos el 20% del monto del cobro realizado indebidamente.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="10">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> INTERUPCION DEL SERVICIO POR FUERZA MAYOR.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">El CLIENTE reconoce que pueden darse factores de interrupciones,&nbsp; fallas o degradaciones en el Servicio originadas por "Caso Fortuito&rdquo; y/o &ldquo;Fuerza Mayor", entendi&eacute;ndose por "Caso Fortuito&rdquo; y/o </span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">&ldquo;Fuerza Mayor" como un acontecimiento ajeno a NETIUM,&nbsp; impredecible e inevitable,&nbsp; que imposibilita al cumplimiento de su obligaci&oacute;n conforme lo establecido en el contrato.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; background: white;">&nbsp;</p>
<ol start="11">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> FACTURACI&Oacute;N.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">La factura ser&aacute; enviada por NETIUM al CLIENTE en forma peri&oacute;dica, por correo electr&oacute;nica o al domicilio que para el efecto el CLIENTE le indique, cuando as&iacute; se contrate.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">Las facturas que NETIUM expide conforme a lo anterior incluir&aacute;n, adem&aacute;s de los requisitos se&ntilde;alados por las leyes respectivas, los siguientes conceptos a) periodo de facturaci&oacute;n, b) Servicios adicionales y en su caso c) Gastos por concepto de cobranza de los servicios.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">El CLIENTE se obliga a cubrir la cantidad total que ampare la factura mensual a m&aacute;s tardar en la fecha de pago.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="12">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> PAGOS.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">El pago de la mensualidad se&ntilde;alada en la car&aacute;tula, EL CLIENTE<strong>,</strong> deber&aacute; hacerlo en los 10 primeros d&iacute;as de cada mes, en el lugar o forma establecida en la misma. Entregando al CLIENTE el comprobante fiscal correspondiente a dicho pago.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">NETIUM, notificar&aacute; por cualquier medio al CLIENTE, incluido el electr&oacute;nico, de cualquier cambio en las condiciones originalmente contratadas.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">NETIUM<strong>, </strong>cuando realice actualizaciones tarifarias y esto implique un aumento en el precio de la prestaci&oacute;n del servicio y/o disminuci&oacute;n en el n&uacute;mero de servicios originalmente contratados, tendr&aacute; la obligaci&oacute;n de notificar al CLIENTE con al menos 15 d&iacute;as naturales de anticipaci&oacute;n a que se lleve a cabo dicha modificaci&oacute;n, EL CLIENTE podr&aacute; solicitar el cumplimiento forzoso y en caso de que no lo cumpla rescindir el mismo, dando por terminado el contrato sin penalidad alguna dentro de los 60 d&iacute;as naturales siguientes a que entren en vigor dichas modificaciones, independientemente de las acciones legales que correspondan para su cumplimiento. </span></p>
<p style="text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline; margin: 0cm 1.45pt .0001pt 0cm;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">Los proveedores no podr&aacute;n incrementar injustificadamente precios por fen&oacute;menos naturales, meteorol&oacute;gicos o contingencias sanitarias.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;">&nbsp;</span></strong><span style="font-family: Arial, sans-serif;">NETIUM se encuentra obligado a entregar en el domicilio del CLIENTE un estado de cuenta y/o factura, el cual deber&aacute; contener el desglose del adeudo total de los conceptos de cada uno de los servicios utilizados, por lo menos 10 d&iacute;as naturales antes de la fecha de pago de los servicios de telecomunicaciones contratados. NETIUM podr&aacute; pactar expresamente con EL CLIENTE que el estado de cuenta y/o factura le sean enviados al correo electr&oacute;nico se&ntilde;alado en la car&aacute;tula o consultada a trav&eacute;s de la p&aacute;gina de Internet </span><a href="http://www.netium.com.mx"><span style="font-family: Arial, sans-serif; color: windowtext;">www.netium.com.mx</span></a><span style="font-family: Arial, sans-serif;">&nbsp; </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">Cuando EL CLIENTE no paga dentro de ese mes NETIUM suspender&aacute; el servicio sin ninguna responsabilidad dentro de los 3 d&iacute;as siguientes apartir de su fecha de pago al incumplimiento de pago, corriendo por cuenta de <strong>&ldquo;EL SUSCRIPTOR&rdquo;</strong> el pago por mensualidad y reconexi&oacute;n vigente, tarifas aprobadas e inscritas en el Registro de Tarifas del Instituto Federal de Telecomunicaciones, mediante folio n&uacute;mero <span style="background: yellow;">__________</span>, las cuales pueden ser consultadas en </span><a href="http://www.ift.org.mx"><span style="font-family: Arial, sans-serif; color: windowtext;">www.ift.org.mx</span></a><span style="font-family: Arial, sans-serif;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">&nbsp;</span><span style="font-family: Arial, sans-serif;">NETIUM </span><span style="font-family: Arial, sans-serif;">deber&aacute; reconectar el servicio dentro de las 24 horas a la fecha en que hubiese liquidado los adeudos y la cuota por reconexi&oacute;n o los cargos de cobranza que estos generen.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="13">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> TERMINOS.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><span style="font-family: Arial, sans-serif;">A la firma de este contrato. EL CLIENTE acepta los t&eacute;rminos y condiciones del servicio, los cuales se pueden consultar en la p&aacute;gina electr&oacute;nica: </span><a href="http://www.netium.com.mx"><span style="font-family: Arial, sans-serif; color: windowtext;">www.netium.com.mx</span></a><span style="font-family: Arial, sans-serif;">.</span></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="14">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> CESION DE DERECHOS.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">EL CLIENTE podr&aacute; ceder los derechos y obligaciones derivados de este contrato a cualquier persona en el mismo domicilio, notificando a NETIUM por escrito y entregando previamente a NETIUM los documentos necesarios para generar la cesi&oacute;n del contrato. El cesonario&nbsp; ser&aacute; responsable ante NETIUM de todos los adeudos anteriores y futuros del servicio.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="15">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> CAUSAS SUSPENSI&Oacute;N.</span></strong></span></li>
</ol>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">NETIUM podr&aacute; suspender total o parcialmente el Servicio, sin necesidad de declaraci&oacute;n judicial o notificaci&oacute;n previa y sin responsabilidad y/o pena alguna de sus partes en los siguientes casos:</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">Por uso indebido del Equipo de Acceso y/o instalar equipo y/o accesorios no homologados por la autoridad competente. </span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">Por declaraci&oacute;n judicial o administrativa.</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">Porque el Cliente no permita a NETIUM realizar las labores de supervisi&oacute;n, cambio, actualizaci&oacute;n, mantenimiento y/o reparaci&oacute;n del Equipo de Acceso y/o tecnolog&iacute;a utilizada para prestar el Servicio que se menciona en la cl&aacute;usula 4.6 del presente Contrato.</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">Por falsedad de las declaraciones o datos proporcionados por el CLIENTE a NETIUM ya sea en el presente Contrato o en cualquier otro documento.</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">Por cualquier otra cosa atribuible al Cliente que impida a NETIUM el cumplimiento del presente Contrato. </span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif;">La suspensi&oacute;n del servicio como consecuencia de las cl&aacute;usulas antes se&ntilde;aladas no implica la cancelaci&oacute;n del Contrato, ni libera de la obligaci&oacute;n al CLIENTE el pago de los saldos remanentes conforme a la duraci&oacute;n del Contrato, por lo que el CLIENTE se obliga a pagar todos los cargos pendientes a favor de NETIUM. No obstante lo anterior, en caso de que el CLIENTE no liquide sus adeudos durante el mes siguiente en que fuera suspendido, NETIUM podr&aacute; cancelar unilateralmente el presente contrato sin necesidad de notificaci&oacute;n previa.</span></p>
<p style="text-align: justify;">&nbsp;</p>
<ol start="16">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif; color: black;">CAUSAS DE RECESION DEL CONTRATO Y TERMINACION ANTICIPADA.</span></strong></span></li>
</ol>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">NETIUM podr&aacute; rescindir del presente Contrato, sin responsabilidad ni pena alguna de su parte, y sin necesidad de declaraci&oacute;n judicial o administrativa, notificando por escrito o cualquier herramienta digital al CLIENTE con cuando menos tres d&iacute;as naturales de anticipada a la fecha en que surta efectos la recesi&oacute;n conforme a los siguientes supuestos:</span></p>
<ol>
<li style="text-align: justify; text-indent: -18.0pt;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp; &nbsp; Por falta de pago del Servicio, de la Tarifa del servicio y/o de los dem&aacute;s cargos adicionales autorizados en los t&eacute;rminos del Presente contrato y de los costos Tarifarios de NETIUM.</span></li>
<li style="text-align: justify; text-indent: -18.0pt;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp; &nbsp; &nbsp;Por incumplimiento de cualquiera de las obligaciones establecidas en el presente Contrato </span></li>
<li style="text-align: justify; text-indent: -18.0pt;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp; &nbsp; Por utilizar el servicio de forma distinta a la se&ntilde;alada en el presente Contrato <strong>o ejercer la reventa del Servicio a otros usuarios</strong>.</span></li>
<li style="text-align: justify; text-indent: -18.0pt;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp; &nbsp; En el evento de que se presente alguno de los casos espec&iacute;ficos de terminaci&oacute;n anticipada que se mencionan en el presente Contrato. </span></li>
<li style="text-align: justify; text-indent: -18.0pt;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp; &nbsp;La cesi&oacute;n, transferencia o cualquier forma similar o an&aacute;loga de transmitir los derechos y obligaciones contenidos en el Contrato, sin la previa autorizaci&oacute;n de NETIUM. </span></li>
<li style="text-align: justify; text-indent: -18.0pt;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp; &nbsp; &nbsp;Las dem&aacute;s que marque la legislaci&oacute;n aplicable. </span></li>
</ol>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">De igual forma el CLIENTE, siempre y cuando se encuentre al corriente de sus pagos por conceptos de Servicios prestados por NETIUM, tendr&aacute; el derecho de terminar anticipadamente el presente Contrato en su totalidad o respecto de alg&uacute;n servicio asociado a este, sin responsabilidad alguna de su parte y sin necesidad de declaraci&oacute;n judicial o administrativa. Para el efecto de recuperar el equipo y los adeudos pendientes, la terminaci&oacute;n surtir&aacute; efectos a los cinco d&iacute;as naturales a partir de la fecha solicitada de terminaci&oacute;n del presente Contrato. No obstante a lo anterior, en caso de que el CLIENTE haya contratado los Servicios bajo alg&uacute;n esquema o Plan Comercial de contrataci&oacute;n por plazos forzosos, y el CLIENTE haya solicitado la terminaci&oacute;n anticipada del Contrato antes de la conclusi&oacute;n del plazo m&iacute;nimo de vigencia del Paquete o Plan Comercial correspondiente, deber&aacute; pagar a NETIUM el cargo correspondiente de las mensualidades o cargos adicionales pendientes por cumplir.</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">NETIUM sin perjuicio de cualquier otro derecho o recurso legal derivado del presente Contrato o de la Ley, tendr&aacute; el derecho de terminar anticipadamente el presente Contrato, sin responsabilidad alguna de su parte y sin necesidad de declaraci&oacute;n judicial o administrativa, notificando por escrito o cualquier herramienta digital al CLIENTE con cuando menos 15 d&iacute;as naturales de anticipaci&oacute;n a la fecha solicitada la terminaci&oacute;n del presente Contrato.</span></p>
<p style="text-align: justify;"><span style="font-size: 9pt; line-height: 115%; font-family: Arial, sans-serif; color: black;">&nbsp;Asimismo NETIUM se reserva el derecho de dar por terminado en forma anticipada el presente Contrato cuando, al momento de efectuar la instalaci&oacute;n, por imposibilidad f&iacute;sica o razones t&eacute;cnicas u obligaci&oacute;n m&aacute;s que de reembolsar al suscriptor cualquier cantidad pagada en forma anticipada para el suministro del Servicio, previo requerimiento por escrito del CLIENTE. Si despu&eacute;s de haber iniciado con la prestaci&oacute;n del Servicio no es factible continuar suministr&aacute;ndolo por causas de fuerza mayor, NETIUM aplicara los t&eacute;rminos y condiciones estipulados en el presente contrato.</span></p>
<p style="text-align: justify;">&nbsp;</p>
<ol start="17">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> INCONDICIONALIDAD PARA CONTRATAR EL SERVICIO.</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">NETIUM no podr&aacute; obligar al CLIENTE a adquirir otros bienes, servicios o valores, como condici&oacute;n para proporcionarle el servicio solicitado, a menos que existan condiciones t&eacute;cnica ineludible.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="18">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> NOTIFICACIONES Y DOMICILIOS</span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">Las partes se&ntilde;alan como domicilios convencionales los establecidos en la CAR&Aacute;TULA.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">Cualquier cambio de domicilio deber&aacute; ser notificado a la otra parte dentro de los 10 d&iacute;as siguientes a la fecha que se efectu&oacute;. En caso de incumplimiento de esta obligaci&oacute;n, NETIUM podr&aacute; dar por terminado el presente contrato. Sin perjuicio de que cualquier notificaci&oacute;n causara efectos en el domicilio anterior.</span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="19">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> MODIFICACIONES </span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt; font-family: Arial, sans-serif;">NETIUM podr&aacute; en todo momento, modificar los t&eacute;rminos y condiciones contenidos en el presente contrato, previa autorizaci&oacute;n por parte de la IFT. Cualquier modificaci&oacute;n a los T&eacute;rminos y Condiciones&nbsp; contenidos en el presente contrato, se deber&aacute; notificar al CLIENTE por parte de NETIUM en forma fehaciente, indicando en todo momento la fecha que entra en vigor de dicha modificaci&oacute;n.<strong>&nbsp;</strong></span></p>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;">&nbsp;</p>
<ol start="20">
<li style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 9pt;"><strong><span style="font-family: Arial, sans-serif;"> JURIDICCI&Oacute;N </span></strong></span></li>
</ol>
<p style="margin-bottom: .0001pt; text-align: justify; line-height: normal; punctuation-wrap: simple; text-autospace: none; vertical-align: baseline;"><span style="font-size: 10.0pt; font-family: 'Arial','sans-serif';"><span style="font-size: 9pt;">Para todo lo relativo a la interpretaci&oacute;n y cumplimiento del presente contrato, las partes se someten a la Jurisdicci&oacute;n de los tribunales competentes del domicilio del CLIENTE se&ntilde;alado en la caratula, o a la jurisdicci&oacute;n de los Tribunales de la Ciudad de M&eacute;xico, DF a elecci&oacute;n del actor, renunciando expresamente a cualquier otro fuero que pudiere corresponderles en raz&oacute;n de sus domicilios presentes o futuros. El CLIENTE en todo momento podr&aacute; acudir ante la procuradur&iacute;a Federal del Consumidor para dirimir cualquier controversia derivada de la interpretaci&oacute;n y/o aplicaci&oacute;n del presente contrato por la v&iacute;a conciliatoria.</span> </span></p>
  </div>


</body>
</html>


<style type="text/css">
  @page{
    margin: 0px;
    margin-top: 15px;
    margin-bottom: 0px;
  }



  #marca-agua{
  position: absolute;
  margin-top: 200px;
  margin-left: 30px;
  z-index: -1;
  filter: grayscale(0%);
}
</style>
<style type="text/css">
      .tbl-fecha td{padding-left: 6px; padding-right: 6px; color:white ; border: 1px solid #e75115; background: #e75115;}
      .tbl-fecha .td-fecha{background: #fab396;}
      .tbl-folio td{padding-left: 27px; padding-right: 27px;color: grey; border:1px solid #e75115; background: #fab396;;}
       .tbl-folio .td-folio{background: white;}
      .tbl-folio{margin-top: 3px}
</style>











