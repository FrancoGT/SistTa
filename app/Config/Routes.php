<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Rutas explícitas (migración desde CodeIgniter 3).
 *
 * CI3 usaba auto-routing: cualquier método público de un controlador era accesible por URL.
 * Aquí solo se exponen las acciones que la aplicación realmente enlaza o a las que envía
 * formularios. Los métodos auxiliares públicos (mostrar_*, get_*, etc.) que usan las vistas
 * a través de $ci ya no son accesibles por URL.
 *
 * Todas las rutas, excepto login, están protegidas por el filtro 'auth' (Config/Filters.php).
 *
 * @var RouteCollection $routes
 */

// Login / Logout (controlador por defecto en CI3: login)
$routes->match(['GET', 'POST'], '/', 'Login::index');
$routes->match(['GET', 'POST'], 'login', 'Login::index');
$routes->match(['GET', 'POST'], 'login/index', 'Login::index');
$routes->get('logout', 'Logout::index');
$routes->get('logout/index', 'Logout::index');

    // Afiliado
    $routes->match(['GET', 'POST'], 'afiliado', 'Afiliado::index');
    $routes->match(['GET'], 'afiliado/actualizar_afiliado/(:segment)', 'Afiliado::actualizar_afiliado/$1');
    $routes->match(['POST'], 'afiliado/actualizar_datos', 'Afiliado::actualizar_datos');
    $routes->match(['GET'], 'afiliado/actualizar_vehiculo/(:segment)', 'Afiliado::actualizar_vehiculo/$1');
    $routes->match(['POST'], 'afiliado/encontrar_vehiculo', 'Afiliado::encontrar_vehiculo');
    $routes->match(['POST'], 'afiliado/guardar_afiliado', 'Afiliado::guardar_afiliado');
    $routes->match(['POST'], 'afiliado/guardar_agregar', 'Afiliado::guardar_agregar');
    $routes->match(['GET', 'POST'], 'afiliado/index', 'Afiliado::index');
    $routes->match(['GET'], 'afiliado/registrar_afiliado', 'Afiliado::registrar_afiliado');
    $routes->match(['GET'], 'afiliado/registrar_vehiculo_afiliado/(:segment)', 'Afiliado::registrar_vehiculo_afiliado/$1');

    // Cobranza
    $routes->match(['GET', 'POST'], 'cobranza', 'Cobranza::index');
    $routes->match(['GET'], 'cobranza/agregar_servicio_afiliado/(:segment)', 'Cobranza::agregar_servicio_afiliado/$1');
    $routes->match(['GET'], 'cobranza/agregar_servicio_conductor/(:segment)', 'Cobranza::agregar_servicio_conductor/$1');
    $routes->match(['GET'], 'cobranza/agregar_servicio_vehiculo/(:segment)', 'Cobranza::agregar_servicio_vehiculo/$1');
    $routes->match(['GET'], 'cobranza/anular_periodo/(:segment)', 'Cobranza::anular_periodo/$1');
    $routes->match(['POST'], 'cobranza/buscar_cobranza_detalle_afiliado', 'Cobranza::buscar_cobranza_detalle_afiliado');
    $routes->match(['POST'], 'cobranza/buscar_cobranza_detalle_conductor', 'Cobranza::buscar_cobranza_detalle_conductor');
    $routes->match(['POST'], 'cobranza/buscar_cobranza_detalle_vehiculo', 'Cobranza::buscar_cobranza_detalle_vehiculo');
    $routes->match(['POST'], 'cobranza/cancelar_pago', 'Cobranza::cancelar_pago');
    $routes->match(['POST'], 'cobranza/cancelar_pago_vehiculo', 'Cobranza::cancelar_pago_vehiculo');
    $routes->match(['POST'], 'cobranza/encontrar_servicio_afiliado', 'Cobranza::encontrar_servicio_afiliado');
    $routes->match(['POST'], 'cobranza/encontrar_servicio_conductor', 'Cobranza::encontrar_servicio_conductor');
    $routes->match(['POST'], 'cobranza/encontrar_servicio_vehiculo', 'Cobranza::encontrar_servicio_vehiculo');
    $routes->match(['POST'], 'cobranza/guardar_nuevo_periodo', 'Cobranza::guardar_nuevo_periodo');
    $routes->match(['POST'], 'cobranza/guardar_nuevo_periodo_vehiculo', 'Cobranza::guardar_nuevo_periodo_vehiculo');
    $routes->match(['POST'], 'cobranza/guardar_servicio_afiliado', 'Cobranza::guardar_servicio_afiliado');
    $routes->match(['POST'], 'cobranza/guardar_servicio_conductor', 'Cobranza::guardar_servicio_conductor');
    $routes->match(['POST'], 'cobranza/guardar_servicio_vehiculo', 'Cobranza::guardar_servicio_vehiculo');
    $routes->match(['GET', 'POST'], 'cobranza/index', 'Cobranza::index');
    $routes->match(['GET'], 'cobranza/nuevo_periodo/(:segment)', 'Cobranza::nuevo_periodo/$1');
    $routes->match(['GET'], 'cobranza/nuevo_periodo_vehiculo/(:segment)', 'Cobranza::nuevo_periodo_vehiculo/$1');
    $routes->match(['GET'], 'cobranza/pagar_deuda/(:segment)', 'Cobranza::pagar_deuda/$1');
    $routes->match(['GET'], 'cobranza/pagar_deuda_vehiculo/(:segment)', 'Cobranza::pagar_deuda_vehiculo/$1');
    $routes->match(['POST'], 'cobranza/quitar_periodo', 'Cobranza::quitar_periodo');
    $routes->match(['GET'], 'cobranza/reasignar_periodo/(:segment)', 'Cobranza::reasignar_periodo/$1');
    $routes->match(['POST'], 'cobranza/restablecer_periodo', 'Cobranza::restablecer_periodo');

    // Conductor
    $routes->match(['GET', 'POST'], 'conductor', 'Conductor::index');
    $routes->match(['GET'], 'conductor/actualizar_conductor/(:segment)', 'Conductor::actualizar_conductor/$1');
    $routes->match(['POST'], 'conductor/actualizar_datos', 'Conductor::actualizar_datos');
    $routes->match(['GET', 'POST'], 'conductor/asignar_vehiculo/(:segment)', 'Conductor::asignar_vehiculo/$1');
    $routes->match(['POST'], 'conductor/encontrar_vehiculo', 'Conductor::encontrar_vehiculo');
    $routes->match(['POST'], 'conductor/guardar_asignar', 'Conductor::guardar_asignar');
    $routes->match(['POST'], 'conductor/guardar_conductor', 'Conductor::guardar_conductor');
    $routes->match(['GET', 'POST'], 'conductor/index', 'Conductor::index');
    $routes->match(['GET'], 'conductor/registrar_conductor', 'Conductor::registrar_conductor');

    // Configuracion
    $routes->match(['GET', 'POST'], 'configuracion', 'Configuracion::index');
    $routes->match(['POST'], 'configuracion/actualizar_configuracion', 'Configuracion::actualizar_configuracion');
    $routes->match(['POST'], 'configuracion/actualizar_datos_servicio', 'Configuracion::actualizar_datos_servicio');
    $routes->match(['GET'], 'configuracion/actualizar_servicio/(:segment)', 'Configuracion::actualizar_servicio/$1');
    $routes->match(['GET'], 'configuracion/aplicar_afiliado/(:segment)', 'Configuracion::aplicar_afiliado/$1');
    $routes->match(['GET'], 'configuracion/aplicar_conductor/(:segment)', 'Configuracion::aplicar_conductor/$1');
    $routes->match(['GET'], 'configuracion/aplicar_unidad/(:segment)', 'Configuracion::aplicar_unidad/$1');
    $routes->match(['POST'], 'configuracion/encontrar_afiliado', 'Configuracion::encontrar_afiliado');
    $routes->match(['POST'], 'configuracion/encontrar_conductor', 'Configuracion::encontrar_conductor');
    $routes->match(['POST'], 'configuracion/encontrar_servicio_afiliado', 'Configuracion::encontrar_servicio_afiliado');
    $routes->match(['POST'], 'configuracion/encontrar_servicio_conductor', 'Configuracion::encontrar_servicio_conductor');
    $routes->match(['POST'], 'configuracion/encontrar_servicio_vehiculo', 'Configuracion::encontrar_servicio_vehiculo');
    $routes->match(['POST'], 'configuracion/encontrar_vehiculo', 'Configuracion::encontrar_vehiculo');
    $routes->match(['POST'], 'configuracion/guardar_servicio', 'Configuracion::guardar_servicio');
    $routes->match(['POST'], 'configuracion/guardar_servicio_afiliado', 'Configuracion::guardar_servicio_afiliado');
    $routes->match(['POST'], 'configuracion/guardar_servicio_conductor', 'Configuracion::guardar_servicio_conductor');
    $routes->match(['POST'], 'configuracion/guardar_servicio_vehiculo', 'Configuracion::guardar_servicio_vehiculo');
    $routes->match(['GET', 'POST'], 'configuracion/index', 'Configuracion::index');
    $routes->match(['GET'], 'configuracion/registrar_servicio', 'Configuracion::registrar_servicio');

    // Dashboard
    $routes->match(['GET', 'POST'], 'dashboard', 'Dashboard::index');
    $routes->match(['GET', 'POST'], 'dashboard/index', 'Dashboard::index');

    // Ingreso_Egreso
    $routes->match(['GET', 'POST'], 'ingreso_egreso', 'Ingreso_Egreso::index');
    $routes->match(['POST'], 'ingreso_egreso/guardar_egreso', 'Ingreso_Egreso::guardar_egreso');
    $routes->match(['POST'], 'ingreso_egreso/guardar_ingreso', 'Ingreso_Egreso::guardar_ingreso');
    $routes->match(['GET', 'POST'], 'ingreso_egreso/index', 'Ingreso_Egreso::index');
    $routes->match(['GET'], 'ingreso_egreso/registrar_egreso', 'Ingreso_Egreso::registrar_egreso');
    $routes->match(['GET'], 'ingreso_egreso/registrar_ingreso', 'Ingreso_Egreso::registrar_ingreso');

    // Reporte
    $routes->match(['GET', 'POST'], 'reporte', 'Reporte::index');
    $routes->match(['GET'], 'reporte/caja_chica', 'Reporte::caja_chica');
    $routes->match(['GET'], 'reporte/clientes_morosos', 'Reporte::clientes_morosos');
    $routes->match(['POST'], 'reporte/generar_caja_chica', 'Reporte::generar_caja_chica');
    $routes->match(['GET'], 'reporte/get_data_servicios_pagados', 'Reporte::get_data_servicios_pagados');
    $routes->match(['GET', 'POST'], 'reporte/index', 'Reporte::index');
    $routes->match(['GET'], 'reporte/servicios_mas_ventas', 'Reporte::servicios_mas_ventas');

    // Usuario
    $routes->match(['GET', 'POST'], 'usuario', 'Usuario::index');
    $routes->match(['POST'], 'usuario/actualizar_datos', 'Usuario::actualizar_datos');
    $routes->match(['GET'], 'usuario/actualizar_usuario/(:segment)', 'Usuario::actualizar_usuario/$1');
    $routes->match(['POST'], 'usuario/guardar_usuario', 'Usuario::guardar_usuario');
    $routes->match(['GET', 'POST'], 'usuario/index', 'Usuario::index');
    $routes->match(['GET'], 'usuario/registrar_usuario', 'Usuario::registrar_usuario');

    // Vehiculo
    $routes->match(['GET', 'POST'], 'vehiculo', 'Vehiculo::index');
    $routes->match(['POST'], 'vehiculo/actualizar_datos', 'Vehiculo::actualizar_datos');
    $routes->match(['GET'], 'vehiculo/actualizar_vehiculo/(:segment)', 'Vehiculo::actualizar_vehiculo/$1');
    $routes->match(['GET'], 'vehiculo/agregar_conductor/(:segment)', 'Vehiculo::agregar_conductor/$1');
    $routes->match(['GET'], 'vehiculo/agregar_propietario/(:segment)', 'Vehiculo::agregar_propietario/$1');
    $routes->match(['GET'], 'vehiculo/cambiar_conductor/(:segment)', 'Vehiculo::cambiar_conductor/$1');
    $routes->match(['GET'], 'vehiculo/cambiar_propietario/(:segment)', 'Vehiculo::cambiar_propietario/$1');
    $routes->match(['POST'], 'vehiculo/confirmar_afiliado_no', 'Vehiculo::confirmar_afiliado_no');
    $routes->match(['POST'], 'vehiculo/confirmar_afiliado_si', 'Vehiculo::confirmar_afiliado_si');
    $routes->match(['POST'], 'vehiculo/confirmar_conductor_no', 'Vehiculo::confirmar_conductor_no');
    $routes->match(['POST'], 'vehiculo/confirmar_conductor_si', 'Vehiculo::confirmar_conductor_si');
    $routes->match(['POST'], 'vehiculo/confirmar_nuevo_conductor_no', 'Vehiculo::confirmar_nuevo_conductor_no');
    $routes->match(['POST'], 'vehiculo/confirmar_nuevo_conductor_si', 'Vehiculo::confirmar_nuevo_conductor_si');
    $routes->match(['POST'], 'vehiculo/confirmar_nuevo_propietario_no', 'Vehiculo::confirmar_nuevo_propietario_no');
    $routes->match(['POST'], 'vehiculo/confirmar_nuevo_propietario_si', 'Vehiculo::confirmar_nuevo_propietario_si');
    $routes->match(['GET'], 'vehiculo/eliminar_conductor/(:segment)', 'Vehiculo::eliminar_conductor/$1');
    $routes->match(['GET'], 'vehiculo/eliminar_propietario/(:segment)', 'Vehiculo::eliminar_propietario/$1');
    $routes->match(['POST'], 'vehiculo/encontrar_afiliado', 'Vehiculo::encontrar_afiliado');
    $routes->match(['POST'], 'vehiculo/encontrar_conductor', 'Vehiculo::encontrar_conductor');
    $routes->match(['POST'], 'vehiculo/encontrar_conductor_add', 'Vehiculo::encontrar_conductor_add');
    $routes->match(['POST'], 'vehiculo/encontrar_nuevo_conductor', 'Vehiculo::encontrar_nuevo_conductor');
    $routes->match(['POST'], 'vehiculo/encontrar_nuevo_propietario', 'Vehiculo::encontrar_nuevo_propietario');
    $routes->match(['POST'], 'vehiculo/encontrar_propietario_add', 'Vehiculo::encontrar_propietario_add');
    $routes->match(['GET', 'POST'], 'vehiculo/index', 'Vehiculo::index');
    $routes->match(['POST'], 'vehiculo/quitar_conductor_no', 'Vehiculo::quitar_conductor_no');
    $routes->match(['POST'], 'vehiculo/quitar_conductor_si', 'Vehiculo::quitar_conductor_si');
    $routes->match(['POST'], 'vehiculo/quitar_propietario_no', 'Vehiculo::quitar_propietario_no');
    $routes->match(['POST'], 'vehiculo/quitar_propietario_si', 'Vehiculo::quitar_propietario_si');
    $routes->match(['POST'], 'vehiculo/recepcionar_vehiculo', 'Vehiculo::recepcionar_vehiculo');
    $routes->match(['GET'], 'vehiculo/registrar_vehiculo', 'Vehiculo::registrar_vehiculo');
