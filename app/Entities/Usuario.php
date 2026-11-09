<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Usuario del panel (tabla login).
 *
 * Entidad de lectura/escritura del registro completo. Para mostrar datos en
 * pantallas o guardarlos en sesión se usan los DTO de App\DTO, que solo
 * llevan los campos necesarios.
 */
class Usuario extends Entity
{
    protected $casts = [
        'id'              => 'integer',
        'rol'             => 'integer',
        'estado'          => 'integer',
        'random'          => 'integer',
        'sucursal_origen' => '?integer',
    ];

    /**
     * login.random = 0 marca a quien debe cambiar la contraseña en su próximo acceso
     */
    public function debeCambiarPassword(): bool
    {
        return (int) ($this->attributes['random'] ?? 0) === 0;
    }

    /**
     * Avatar para el menú: la imagen guardada o, si no hay, un <img> que el
     * navegador completa con las iniciales (initial.js)
     */
    public function avatarHtml(): string
    {
        $avatar = (string) ($this->attributes['avatar'] ?? '');

        if ($avatar === '') {
            return '<img class="avataradmin" data-name="' . esc((string) ($this->attributes['nombre'] ?? ''), 'attr')
                . '" data-color="' . esc((string) ($this->attributes['color_avatar'] ?? ''), 'attr') . '">';
        }

        return '<img src="data:image/jpeg;base64,' . base64_encode($avatar) . '" style="border-radius: 100%" width="34" height="34"/>';
    }
}
