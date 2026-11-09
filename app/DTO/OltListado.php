<?php

namespace App\DTO;

/**
 * Fila de la lista de OLT (Fiber > OLT).
 * Sin credenciales: antes la respuesta incluía user/pass de Telnet y las
 * comunidades SNMP de lectura y escritura de cada OLT.
 */
final readonly class OltListado
{
    public function __construct(
        public int $id,
        public string $nombre,
        public string $ip,
        public string $modelo,
        public string $imagen,
        public string $temperatura,
        public string $conexion,
        public string $estado,
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
            ip: (string) $fila['ip'],
            modelo: (string) ($fila['modelo'] ?? ''),
            imagen: (string) ($fila['image_olt'] ?? ''),
            temperatura: (string) ($fila['temperatura'] ?? ''),
            conexion: (string) ($fila['conexion'] ?? ''),
            estado: (string) ($fila['estado'] ?? ''),
        );
    }
}
