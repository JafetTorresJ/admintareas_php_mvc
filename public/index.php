<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\TareaController;
use Controllers\DashboardController;
use Controllers\LoginController;
use MVC\Router;
$router = new Router();

//Login 
$router->get('/',[LoginController::class,'login']);
$router->post('/',[LoginController::class,'login']);
$router->get('/logout',[LoginController::class,'logout']);


//Crear cuenta
$router->get('/crear',[LoginController::class,'crear']);
$router->post('/crear',[LoginController::class,'crear']);

//formulario de olvide mi pasword

$router->get('/olvide',[LoginController::class,'olvide']);
$router->post('/olvide',[LoginController::class,'olvide']);


//Colocar el nuevo password
$router->get('/reestablecer',[LoginController::class,'reestablecer']);
$router->post('/reestablecer',[LoginController::class,'reestablecer']);


//Confirmacion de cuenta
$router->get('/mensaje',[LoginController::class,'mensaje']);
$router->get('/confirmar',[LoginController::class,'confirmar']);

//zona para dashboards

$router->get('/dashboard',[DashboardController::class, 'index']);
$router->get('/crear-proyectos',[DashboardController::class, 'crear']);
$router->post('/crear-proyectos',[DashboardController::class, 'crear']);
$router->get('/proyecto',[DashboardController::class, 'proyecto']);
$router->get('/perfil',[DashboardController::class, 'perfil']);
$router->post('/perfil',[DashboardController::class, 'perfil']);
$router->get('/cambiar-password',[DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password',[DashboardController::class, 'cambiar_password']);
// API para las tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);
//eliminar proyecto
$router->post('/proyecto/eliminar', [DashboardController::class, 'eliminar_proyecto']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();