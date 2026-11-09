<?php

namespace App\Models;

use App\DTO\UsuarioListado;
use App\Entities\Usuario;
use App\Libraries\Contrasena;
use CodeIgniter\Model;

/**
 * Usuarios del panel (tabla login).
 */
class UsuarioModel extends Model
{
    protected $table         = 'login';
    protected $primaryKey    = 'id';
    protected $returnType    = Usuario::class;
    protected $useTimestamps = false;
    protected $allowedFields = [
        'username', 'password', 'oldPassword', 'random', 'nombre', 'rol', 'estado', 'correo', 'movil',
        'nodo', 'sucursal', 'sucursal_origen', 'avatar', 'color_avatar',
    ];

    /**
     * Verifica usuario y contraseña de un usuario activo. Si la contraseña
     * estaba en MD5 la vuelve a guardar con password_hash().
     */
    public function autenticar(string $username, string $password): ?Usuario
    {
        $candidatos = $this->select('login.*, rol.rol AS nombre_rol')
            ->join('rol', 'rol.id = login.rol')
            ->where('login.username', $username)
            ->where('login.estado', 1)
            ->findAll();

        foreach ($candidatos as $usuario) {
            if (! Contrasena::verificar($password, (string) $usuario->password)) {
                continue;
            }

            if (Contrasena::necesitaRehash((string) $usuario->password) && $this->passwordAdmiteHash()) {
                $usuario->password = Contrasena::hash($password);
                $this->update($usuario->id, ['password' => $usuario->password]);
            }

            return $usuario;
        }

        return null;
    }

    /**
     * Guarda una contraseña nueva y pasa la actual al historial.
     * La validación de reutilización (Contrasena::yaUsada) la hace quien llama.
     */
    public function cambiarPassword(Usuario $usuario, string $nueva): bool
    {
        return $this->update($usuario->id, [
            'password'    => Contrasena::hash($nueva),
            'random'      => 1,
            'oldPassword' => Contrasena::agregarAlHistorial((string) $usuario->oldPassword, (string) $usuario->password),
        ]);
    }

    /**
     * Lista de personal visible para las sucursales del usuario actual.
     *
     * @param string $filtroSucursal condición generada por Class_permiso_sucursal::get_permiso_sucursal_login()
     *
     * @return list<UsuarioListado>
     */
    public function listado(string $filtroSucursal): array
    {
        $filas = $this->db->table('login l')
            ->select('l.id, l.nombre, l.username, l.correo, l.estado, r.rol, s.sucursal')
            ->join('rol r', 'r.id = l.rol')
            ->join('sucursal s', 'l.sucursal_origen = s.id')
            ->where($filtroSucursal)
            ->orderBy('l.id', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(UsuarioListado::desdeFila(...), $filas);
    }

    /**
     * Evita guardar un hash de 60 caracteres en la columna de 32 (MD5) si aún no
     * se ejecutó la migración AmpliarPasswordLogin: quedaría truncado y el
     * usuario no podría volver a entrar
     */
    private function passwordAdmiteHash(): bool
    {
        $campo = $this->db->query(
            "SELECT CHARACTER_MAXIMUM_LENGTH AS largo FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'login' AND COLUMN_NAME = 'password'"
        )->getRow();

        return $campo !== null && (int) $campo->largo >= 60;
    }
}
