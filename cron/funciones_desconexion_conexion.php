<?php
include "conexion.php";
function GetFallosLog(string $fechaFormatMYSQL,int $rangoDeHoras): array
{
  $fecha2=Date('Y-m-d H:i:s',strtotime("-$rangoDeHoras hour", strtotime($fechaFormatMYSQL)));
  $conn = conectar();
  $sql = "SELECT u.id,t.cron_fallos info,(
            SELECT count(l.id) FROM logsistema l WHERE (
                       l.fechalog BETWEEN \"$fecha2\" and \"$fechaFormatMYSQL\"
                   ) and (l.idcliente=u.id and l.tipolog=1
                )
          ) fallosdobles from usuarios2 u INNER JOIN tbldatauserprivate t ON t.iduser=u.id WHERE u.estado=\"ACTIVO\"";
  $result = $conn->query($sql);
  $temp = array();
  if ($result) {
    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $fallosdobles = $row['fallosdobles'];
        $info = $row['info'];
        $temp[] = [
          "id" => $id,
          "info" => $info,
          "fallos" => $fallosdobles != 0 ? $fallosdobles / 2 : $fallosdobles
        ];
      }
      /* array_multisort(array_column($temp, 'fallos'), SORT_DESC, SORT_NUMERIC, $temp); */
    }
  }
  $result->close();
  return $temp;
}

function InsertTblSoporte(int $idcliente, String $fechaFormatMYSQL): int
{
  $conn = conectar();
  $sql = "INSERT INTO `soporte`
            (`id`, `idcliente`, `idsoporte`, `asunto`, `fecha_soporte`, `estado`, `operador`, `fecha_cerrado`, `procede`,
             `solicitante`, `fechavisita`, `fechaendvisita`, `turno`, `agendado`, `lastdate`, `dp`, `cun`, `color`, `tipo`, `motivo_cierre`)
          VALUES
            (NULL, '$idcliente', '0', 'Desconexion constante[SISTEMA]', '$fechaFormatMYSQL', 'abierto', 'SISTEMA', '0000-00-00 00:00:00',
             '', '', '$fechaFormatMYSQL', '0000-00-00 00:00:00', ' (N/A)', 'Oficina', '$fechaFormatMYSQL', '1', '', '', '', '')";
$result = $conn->query($sql);
$id=$result?$conn->insert_id:0;
return $id;
}

function InsertTblTikect($idTblSoporte, int $idcliente, String $fechaFormatMYSQL,int $rangoDeHoras): bool
{
  $fecha2 = Date('Y-m-d H:i:s', strtotime("-$rangoDeHoras hour", strtotime($fechaFormatMYSQL)));
  $conn = conectar();
  $sql = "INSERT INTO `ticket`
          (`id`, `idticket`, `contenido`, `adjunto`, `fecha_hora`, `idtecnico`, `state`, `nota`)
        VALUES
          (NULL, '$idTblSoporte', '<p>Desconexion constante $fecha2 a $fechaFormatMYSQL (DETECTADO POR EL SISTEMA)</p>', '', '$fechaFormatMYSQL', '0', '0', '0')";
  $result = $conn->query($sql);
  $insert = $result?true:false;
  return $insert;
}
function InsertLog(int $idTblSoporte,int $idcliente,string $fechaFormatMYSQL): void
{
  $conn = conectar();
  $sql = "INSERT INTO `logsistema`
             (`id`, `log`, `fechalog`, `operador`, `idcliente`, `tipolog`, `tipo`)
          VALUES
            (NULL, 'Ticket abiero <b>ID: $idTblSoporte', '$fechaFormatMYSQL', '0', '$idcliente', '0', 'Tickets')";
  $conn->query($sql);
}

function changeInfo(int $idcliente): void
{
  $conn = conectar();
  $sql = "UPDATE `tbldatauserprivate` SET cron_fallos=1 WHERE iduser=$idcliente";
  $conn->query($sql);
}
