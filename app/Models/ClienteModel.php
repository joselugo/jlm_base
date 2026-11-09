<?php

namespace App\Models;

use App\DTO\ClienteListado;
use App\Entities\Cliente;
use App\Libraries\FiltroSucursal;
use CodeIgniter\Model;

/**
 * Clientes (tabla usuarios2).
 *
 * Se irá ampliando a medida que se migren los casos de uso de Model_clientes.
 */
class ClienteModel extends Model
{
    protected $table         = 'usuarios2';
    protected $primaryKey    = 'id';
    protected $returnType    = Cliente::class;
    protected $useTimestamps = false;
    protected $allowedFields = [
        'nombre', 'estado', 'correo', 'telefono', 'movil', 'cedula', 'pasarela', 'codigo',
        'direccion_principal', 'sucursal',
    ];

    /**
     * Lista de clientes visibles para las sucursales del usuario.
     *
     * Reemplaza a Model_clientes::get_all_clientes, get_clientes y
     * get_clientes_retirados, que traían u.* con un JOIN a tblavisouser sin
     * usar y "GROUP BY ta.cliente": los clientes sin tblavisouser (NULL) se
     * juntaban en una sola fila, y MySQL 8 rechaza ese GROUP BY.
     *
     * @param string|null $estado         null = todos
     *
     * @return list<ClienteListado>
     */
    public function listado(?string $estado): array
    {
        $builder = $this->db->table('usuarios2 u')
            ->select('u.id, u.nombre, u.estado, u.direccion_principal, u.movil, u.correo, su.sucursal, su.color_su')
            ->join('sucursal su', 'su.id = u.sucursal')
            ->orderBy('u.id', 'ASC');
        // Solo clientes de las sucursales del usuario (antes usaba t.sucursal sin unir
        // tblservicios: error 500 para cualquier usuario sin todas las sucursales).
        FiltroSucursal::aplicar($builder, 'u.sucursal');

        if ($estado !== null) {
            $builder->where('u.estado', $estado);
        }

        return array_map(ClienteListado::desdeFila(...), $builder->get()->getResultArray());
    }
}
