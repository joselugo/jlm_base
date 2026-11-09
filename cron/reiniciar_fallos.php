<?php
include "conexion.php";
$conn = conectar();
$sql= "UPDATE `tbldatauserprivate` SET cron_fallos=0";
$conn->query($sql);
?>