<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * login.password era varchar(32), justo un MD5. password_hash() (bcrypt)
 * genera 60 caracteres; 255 deja margen para cambiar de algoritmo.
 *
 * Debe ejecutarse antes de desplegar el código que guarda contraseñas con
 * password_hash(): si no, el hash se trunca y el usuario no puede entrar.
 */
class AmpliarPasswordLogin extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('login', [
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
        ]);
    }

    public function down()
    {
        // Solo es seguro si todas las contraseñas siguen en MD5 (32 caracteres)
        $largas = $this->db->table('login')->where('CHAR_LENGTH(password) >', 32, false)->countAllResults();
        if ($largas > 0) {
            throw new \RuntimeException("No se puede revertir: {$largas} usuarios ya tienen contraseñas con password_hash()");
        }

        $this->forge->modifyColumn('login', [
            'password' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => false],
        ]);
    }
}
