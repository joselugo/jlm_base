<?php

namespace App\DTO;

use App\Entities\Cliente;

/**
 * Fila de la lista de clientes (Clientes > Usuarios).
 * Solo las columnas que muestra la tabla.
 */
final readonly class ClienteListado
{
    // Etiqueta del estado en la lista; cualquier otro estado usa la de suspendido
    private const ETIQUETAS = [
        Cliente::ACTIVO   => 'label-success',
        Cliente::RETIRADO => 'label-danger',
    ];

    public function __construct(
        public string $id,
        public string $nombre,
        public string $estado,
        public string $direccion,
        public string $movil,
        public string $correo,
        public string $sucursal,
        public string $colorSucursal,
    ) {
    }

    /**
     * @param array<string, mixed> $fila
     */
    public static function desdeFila(array $fila): self
    {
        return new self(
            id: (string) $fila['id'],
            nombre: (string) $fila['nombre'],
            estado: (string) $fila['estado'],
            direccion: (string) ($fila['direccion_principal'] ?? ''),
            movil: (string) ($fila['movil'] ?? ''),
            correo: (string) ($fila['correo'] ?? ''),
            sucursal: (string) ($fila['sucursal'] ?? ''),
            colorSucursal: (string) ($fila['color_su'] ?? ''),
        );
    }

    public function claseEtiqueta(): string
    {
        return self::ETIQUETAS[$this->estado] ?? 'label-warning';
    }

    /**
     * Color de la sucursal solo si es un color hexadecimal (va dentro de un style)
     */
    public function colorSeguro(): string
    {
        return preg_match('/^#[0-9A-Fa-f]{3,8}$/', $this->colorSucursal) === 1 ? $this->colorSucursal : '#777777';
    }
}
