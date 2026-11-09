<html>
<head>
  <meta charset="UTF-8">
  <title>Listas</title>
</head>
<body>
  <div id="marca-agua"><img alt="" src="<?=base_url()?>public/images/logo-netium-agua.png" style="width: 660px; "></div>
<table border="0" cellpadding="1" cellspacing="1" style="width:100%;">
  <tbody>
    <tr>
      <td width="70%">
      <p>&nbsp;</p>
      <p align="center"><span style="font-family:verdana,geneva,sans-serif;"><strong>LISTA UNIDADES</strong></span></p>
      </td>
      <td width="30%"><img alt="" src="<?=base_url()?>public/images/logo-netium.png" style="width: 100px; height: 90px; float: right; text-align: right;" /></td>
    </tr>
  </tbody>
</table>
<p></p>


<table width="100%"  style="border: 1px solid">
  <tr>
      <th style="text-align:left">Folio</th>
       <th style="text-align:left">Tipo</th> 
       <th style="text-align:left">Matrícula</th> 
       <th style="text-align:left">Socio</th> 
       <th style="text-align:left">N° Afiliado</th> 
       <th style="text-align:left">Sindicato</th>
       <th style="text-align:left">Fecha alta</th>
       <th style="text-align:left">Modo</th>
       <th style="text-align:left">Status</th>
  </tr>
 
  <?php 

 foreach ($unidades as $items) {
   echo "<tr>";
         echo "<td>".$items->id."</td>";
         echo "<td>".$items->tipounidad."</td>";
         echo "<td>".$items->matricula."</td>";
         echo "<td>".$items->nombre.' '.$items->apellidos.'</td>';
         echo "<td>".$items->codigosindicato."</td>";
          echo "<td>".$items->nombre_sindicato."</td>";
         echo "<td>".date('d/m/Y',strtotime($items->fechaalta))."</td>";
         echo "<td>".$items->modounidad."</td>";
         echo "<td>".$items->estado."</td>";
        
   
   echo "</tr>";
 }

 ?>
</table>



<p style="font-size:11px">Fecha de impresión <?=date("d/m/Y h:i a")?>.</p>
<style type="text/css">

@page{
  margin-left: 50px;
  margin-top: 20px;
}

table tr:nth-child(even) {
    background-color: #eee;
}

table tr:nth-child(odd) {
    background-color: #fff;
}

#marca-agua{
  position: absolute;
  margin-top: 150px;
  z-index: -1;
  filter: grayscale(0%);

  width: 100%;
   padding-left: 160px
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
