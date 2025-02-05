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

// Rutas para el controlador de Veterinario
$routes->get('veterinarios', 'VeterinarioControlador::index');  // Mostrar lista de veterinarios
$routes->get('veterinarios/crear', 'VeterinarioControlador::guardarVeterinario');  // Crear veterinario
$routes->post('veterinarios/guardar', 'VeterinarioControlador::guardarVeterinario');  // Guardar veterinario
$routes->get('veterinarios/editar/(:num)', 'VeterinarioControlador::guardarVeterinario/$1');  // Editar veterinario
$routes->post('veterinarios/editar/(:num)', 'VeterinarioControlador::guardarVeterinario/$1');  // Guardar cambios veterinario
$routes->get('veterinarios/eliminar/(:num)', 'VeterinarioControlador::borrar/$1');  // Eliminar veterinario

// Rutas para el controlador de Mascota
$routes->get('mascotas', 'MascotaControlador::index');  // Listado de mascotas
$routes->get('mascotas/crear', 'MascotaControlador::guardarMascota');  // Formulario para crear mascota
$routes->post('mascotas/guardar', 'MascotaControlador::guardarMascota');  // Guardar mascota
$routes->get('mascotas/editar/(:num)', 'MascotaControlador::guardarMascota/$1');  // Editar mascota
$routes->post('mascotas/editar/(:num)', 'MascotaControlador::guardarMascota/$1');  // Guardar cambios en mascota
$routes->get('mascotas/eliminar/(:num)', 'MascotaControlador::borrar/$1');  // Eliminar mascota

// Rutas para MedicamentoController
$routes->get('medicamentos', 'MedicamentoControlador::index');
$routes->get('medicamentos/crear', 'MedicamentoControlador::guardarMedicamento');
$routes->post('medicamentos/guardar', 'MedicamentoControlador::guardarMedicamento');
$routes->get('medicamentos/editar/(:num)', 'MedicamentoControlador::guardarMedicamento/$1');
$routes->post('medicamentos/editar/(:num)', 'MedicamentoControlador::guardarMedicamento/$1');
$routes->get('medicamentos/eliminar/(:num)', 'MedicamentoControlador::borrar/$1');


//login
$routes->get(from: 'register', to: 'ClienteControlador::register'); // Página de registro
$routes->post(from: 'register/process', to: 'ClienteControlador::Registro'); // Procesar registro
$routes->get(from: 'login', to: 'ClienteControlador::login'); // Página de login
$routes->post(from: 'login/process', to: 'ClienteControlador::InicioSesion'); // Procesar login
$routes->get(from: 'logout', to: 'ClienteControlador::logout'); // Cerrar sesión
$routes->get(from: 'dashboard', to: 'ClienteControlador::dashboard'); // Perfil de usuario

//ROL
$routes->get('rol', 'RolControlador::index'); // Muestra la lista de clientes con roles
$routes->post('RolControlador/filtrarPorRol', 'RolControlador::filtrarPorRol'); // Filtra clientes según el rol seleccionado

//agregar usuario 
$routes->post('/agregar_usuario', 'ClienteControlador::AgregarUsuario'); // Procesa el formulario y agrega el usuario

