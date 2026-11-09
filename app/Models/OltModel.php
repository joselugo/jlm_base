<?php

namespace App\Models;

use App\DTO\OltListado;
use CodeIgniter\Model;

/**
 * OLT (tabla olt).
 *
 * Las credenciales (user, pass, comunidades SNMP) solo se usan en el
 * servidor para conectarse al equipo; nunca forman parte de un listado.
 */
class OltModel extends Model
{
    protected $table         = 'olt';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'nombre', 'ip', 'user', 'pass', 'coordenadas', 'modelo', 'version', 'serial', 'pon', 'prompt',
        'port_telnet', 'port_snmp', 'temperatura', 'comunidad_lectura', 'comunidad_escritura', 'conexion', 'estado',
    ];

    /**
     * @return list<OltListado>
     */
    public function listado(): array
    {
        $filas = $this->db->table('olt o')
            ->select('o.id, o.nombre, o.ip, o.temperatura, o.conexion, o.estado, m.modelo, m.imagen AS image_olt')
            ->join('olt_model m', 'm.id = o.modelo', 'left')
            ->orderBy('o.id', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(OltListado::desdeFila(...), $filas);
    }
}
