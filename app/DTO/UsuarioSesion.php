<?php

namespace App\DTO;

use App\Entities\Usuario;

/**
 * Datos del usuario que se guardan en la sesión al iniciar sesión.
 */
final readonly class UsuarioSesion
{
    public function __construct(
        public int $id,
        public string $username,
        public string $nombre,
        public string $avatarHtml,
        public string $nodos,
        public string $sucursales,
        public string $rol,
    ) {
    }

    public static function desdeUsuario(Usuario $usuario): self
    {
        return new self(
            id: (int) $usuario->id,
            username: (string) $usuario->username,
            nombre: (string) $usuario->nombre,
            avatarHtml: $usuario->avatarHtml(),
            nodos: (string) $usuario->nodo,
            sucursales: (string) $usuario->sucursal,
            rol: (string) $usuario->nombre_rol,
        );
    }

    /**
     * Claves de $_SESSION que usa el resto del panel
     *
     * @return array<string, int|string>
     */
    public function aSesion(): array
    {
        return [
            'id_usuario8291' => $this->id,
            'usuario'        => $this->username,
            'nombre'         => $this->nombre,
            'avatar'         => $this->avatarHtml,
            'nodos'          => $this->nodos,
            'sucursales'     => $this->sucursales,
            'rol'            => $this->rol,
        ];
    }
}
