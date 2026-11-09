<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Index::index');

// El resto de URLs se resuelven por auto-routing legacy (Config\Routing::$autoRoute
// y Config\Feature::$autoRoutesImproved = false): /controlador/metodo/args
