<?php
include("conexion.php");
$conn = conectar();
$sql = "UPDATE `login` SET `check_cumple_dia`=0";
$result = $conn->query($sql);

