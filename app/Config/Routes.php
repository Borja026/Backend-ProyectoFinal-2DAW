<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



//  Pistas
$routes->resource('pistas');
$routes->get('pistas', 'Pistas::index');
$routes->get('pistas/(:segment)', 'Pistas::show');
$routes->post('pistas', 'Pistas::create');
$routes->put('pistas/(:segment)', 'Pistas::update');
$routes->delete('pistas/(:segment)', 'Pistas::delete');


//  Empleados
$routes->resource('empleados');
$routes->get('empleados', 'Empleados::index');
$routes->get('empleados/(:segment)', 'Empleados::show');
$routes->post('empleados', 'Empleados::create');
$routes->put('empleados/(:segment)', 'Empleados::update');
$routes->delete('empleados/(:segment)', 'Empleados::delete');



//  Clientes
$routes->resource('clientes');
$routes->get('clientes', 'Clientes::index');
$routes->get('clientes/(:segment)', 'Clientes::show');
$routes->post('clientes', 'Clientes::create');
$routes->put('clientes/(:segment)', 'Clientes::update');
$routes->delete('clientes/(:segment)', 'Clientes::delete');



// PistasClientes
$routes->get('pistasClientes', 'PistasClientes::index');
$routes->post('pistasClientes', 'PistasClientes::create');

$routes->get('pistasClientes/(:segment)', 'PistasClientes::show/$1');
$routes->put('pistasClientes/(:segment)', 'PistasClientes::update/$1');
$routes->delete('pistasClientes/(:segment)', 'PistasClientes::delete/$1');



//  Galería
$routes->resource('galeria');
$routes->get('galeria', 'Galeria::index');
$routes->get('galeria/(:segment)', 'Galeria::show');
$routes->post('galeria', 'Galeria::create');
$routes->put('galeria/(:segment)', 'Galeria::update');
$routes->delete('galeria/(:segment)', 'Galeria::delete');



//  Categorías
$routes->resource('categorias');
$routes->get('categorias', 'Categorias::index');
$routes->get('categorias/(:segment)', 'Categorias::show');
$routes->post('categorias', 'Categorias::create');
$routes->put('categorias/(:segment)', 'Categorias::update');
$routes->delete('categorias/(:segment)', 'Categorias::delete');










// ----------------------------------------------------------------------------------------------------------

//* Admin Panel - MENÚ  
$routes->get('admin', 'AdminPanel::menu');

//* Admin Panel - Clienates
$routes->get('admin/clientes', 'AdminPanelClientes::clientes');
$routes->post('admin/clientes/guardar', 'AdminPanelClientes::guardarCliente');
$routes->get('admin/clientes/eliminar', 'AdminPanelClientes::eliminarCliente');

//* Admin Panel - Empleados
$routes->get('admin/empleados', 'AdminPanelEmpleados::empleados');
$routes->post('admin/empleados/guardar', 'AdminPanelEmpleados::guardarEmpleado');
$routes->get('admin/empleados/eliminar', 'AdminPanelEmpleados::eliminarEmpleado');

//* Admin Panel - PistasClientes
$routes->get('/admin/pistasClientes', 'AdminPanelPistasClientes::pistasClientes');
$routes->post('/admin/pistasClientes/guardar', 'AdminPanelPistasClientes::guardarReserva');
$routes->get('/admin/pistasClientes/eliminar', 'AdminPanelPistasClientes::eliminarReserva');



//* Admin Panel - Pistas
$routes->get('admin/pistas', 'AdminPanelPistas::pistas');
$routes->post('admin/pistas/guardar', 'AdminPanelPistas::guardarPista');
$routes->get('admin/pistas/eliminar', 'AdminPanelPistas::eliminarPista');

//* Admin Panel - Galería
$routes->get('admin/galeria', 'AdminPanelGaleria::galeria');
$routes->post('admin/galeria/guardar', 'AdminPanelGaleria::guardarImagen');
$routes->get('admin/galeria/eliminar', 'AdminPanelGaleria::eliminarImagen');

//* Admin Panel - Categorías
$routes->get('admin/categorias', 'AdminPanelCategorias::categorias');
$routes->post('admin/categorias/guardar', 'AdminPanelCategorias::guardarCategoria');
$routes->get('admin/categorias/eliminar', 'AdminPanelCategorias::eliminarCategoria');