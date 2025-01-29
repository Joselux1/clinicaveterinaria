<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');


// Rutas para ClienteController
$routes->get('clientes', 'ClienteControlador::index');
$routes->get('clientes/crear', 'ClienteControlador::guardarCliente');
$routes->post('clientes/guardar', 'ClienteControlador::guardarCliente');
$routes->get('clientes/editar/(:num)', 'ClienteControlador::guardarCliente/$1');
$routes->post('clientes/editar/(:num)', 'ClienteControlador::guardarCliente/$1');
$routes->get('clientes/eliminar/(:num)', 'ClienteControlador::borrar/$1');

// Rutas para el controlador de Citas
$routes->get('/citas', 'CitasControlador::index');  // Muestra el listado de citas
$routes->get('/citas/crear', 'CitasControlador::crear');  // Formulario para crear una cita
$routes->post('/citas/guardar', 'CitasControlador::guardar');  // Guardar una nueva cita
$routes->get('/citas/editar/(:num)', 'CitasControlador::editar/$1');  // Editar una cita
$routes->post('/citas/editar/(:num)', 'CitasControlador::editar/$1');  // Editar una cita
$routes->post('/citas/actualizar/(:num)', 'CitasControlador::actualizar/$1');  // Actualizar una cita
$routes->get('/citas/eliminar/(:num)', 'CitasControlador::eliminar/$1');  // Eliminar una cita
$routes->get('/citas/baja/(:num)', 'CitasControlador::baja/$1');  // Marcar una cita como "baja"

