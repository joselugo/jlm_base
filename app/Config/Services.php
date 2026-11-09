<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    // Claves del JWT que se emite al iniciar sesión. Se leen del disco
    // (rsa/ está denegado por HTTP); rutas configurables en .env
    public static function getSecretKey()
    {
        return file_get_contents(env('jwt.privateKeyPath') ?: ROOTPATH . 'rsa/private.rsa');
    }

    public static function getPublicKey()
    {
        return file_get_contents(env('jwt.publicKeyPath') ?: ROOTPATH . 'rsa/public.rsa.pub');
    }
}
