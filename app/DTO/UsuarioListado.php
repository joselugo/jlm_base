<?php

namespace App\DTO;

/**
 * Fila de la lista de personal (Ajustes > Gestión de personal).
 * Solo las columnas que muestra la tabla: sin avatar (BLOB) ni contraseñas.
 */
final readonly class UsuarioListado
{
    public function __construct(
        public int $id,
        public string $nombre,
        public string $username,
        public string $correo,
        public bool $habilitado,
        public string $rol,
        public string $sucursal,
    ) {
    }

    /**
     * @param array<string, mixed> $fila
     */
    public static function desdeFila(array $fila): self
    {
        return new self(
            id: (int) $fila['id'],
            nombre: (string) $fila['nombre'],
            username: (string) $fila['username'],
            correo: (string) ($fila['correo'] ?? ''),
            habilitado: (int) $fila['estado'] === 1,
            rol: (string) $fila['rol'],
            sucursal: (string) $fila['sucursal'],
        );
    }
}
