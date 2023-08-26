<?php

namespace Controllers;

use Model\Tarea;
use Model\Usuario;
use Model\Proyecto;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){
         session_start();

          isAuth();
          $id = $_SESSION['id'];

          $proyectos = Proyecto::belongsTo('propietarioId', $id);

         

        $router->render('dashboard/index',[
           'titulo' => 'Proyectos',
           'proyectos' => $proyectos
        ]);
    }

    public static function crear(Router $router){
        session_start();
        isAuth();
        
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $proyecto = new Proyecto($_POST);
            //validacion

            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){ //si no hay alertas podemos realizar la siguiente acciones
              //generar una URL Unica
              $hash = md5(uniqid());
               $proyecto->url = $hash;

               //almacenaar el creador del proyecto
               $proyecto->propietarioId = $_SESSION['id'];
                // guardar el proyecto
                 $proyecto->guardar();    
              
                 // redireccionar
                 header('Location: /proyecto?id='. $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear Proyectos',
            'alertas' => $alertas
         ]);
    }
    public static function proyecto(Router $router){
        session_start();     
        isAuth(); 

      
        $token = $_GET['id'];
       // debuguear($token);
       if(!$token) header('Location: /dashboard');
      //revisar que la persona que visita el proyecto es quien lo creo
   
      $proyecto = Proyecto::where('url', $token);
 
      if($proyecto->propietarioId !== $_SESSION['id']){
    header('Location: /dashboard');
  }
        
        $router->render('dashboard/proyecto',[
                    'titulo' => $proyecto->proyecto
              ]);

    }

    public static function perfil(Router $router){
        session_start();
        isAuth(); 

      $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
       $usuario->sincronizar($_POST);
       $alertas = $usuario->validar_perfil();
       if(empty($alertas)){
//verificar que no se dupliquen los corros electronicos
         
$existeUsuario = Usuario::where('email', $usuario->email);

  if($existeUsuario && $existeUsuario->id !== $usuario->id ){
   //si hay un usuario mostramos un mensaje de error
   Usuario::setAlerta('error', 'Email ya existente, intenta con otro');
    $alertas = $usuario->getAlertas();

  }else{
    //si no hay un usuario con el mismo correro guardamos el registro
    //guardar usuario
    $usuario->guardar();
    Usuario::setAlerta('exito', 'Guardado Correctamente');
    $alertas = $usuario->getAlertas();
    //asignar el nombre actualizado a la barra
    $_SESSION['nombre'] = $usuario->nombre;

}


       }
       

    }

        $router->render('dashboard/perfil',[
          
          
            'titulo' => 'Tu Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
         ]);
    }

    public static function cambiar_password(Router $router){
            session_start();
            isAuth();
            $alertas = [];
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $usuario = Usuario::find($_SESSION['id']);
              //sincornizar con los datos del usuario
              $usuario->sincronizar($_POST);

              $alertas =  $usuario->nuevo_password();
                if(empty($alertas)){
                     $resultado = $usuario->comprobar_password();
                     if($resultado){
                       
                        $usuario->password = $usuario->password_nuevo;
                        //Eliminar propiedades no necesarias
                        unset($usuario->password_actual);
                        unset($usuario->password_nuevo);

                        //hashear el nuevo password
                        $usuario->hashPassword();

                        //actualizar el nuevo password
                     $resultado = $usuario->guardar();
                      
                     if($resultado){
                        Usuario::setAlerta('exito', 'Password guardado correctamente');
                        $alertas = $usuario->getAlertas();
                     }
                      
                        

                     }else{
                        Usuario::setAlerta('error', 'Password incorrecto');
                        $alertas = $usuario->getAlertas();
                     }
                }
            }
        $router->render('dashboard/cambiar-password', [
           'titulo' => 'Cambiar Password',
           'alertas' => $alertas
        ]);

    }

    public static function eliminar_proyecto(){
      session_start();
      isAuth();

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id = $_POST['id'];
          $id = filter_var($id, FILTER_VALIDATE_INT);
          

        
        
            if ($id) {
              $proyecto = Proyecto::find($id);
              if ($proyecto->propietarioId === $_SESSION['id']) {
                  $id = $_POST['id'];
                  $proyecto = Proyecto::find($id);
                  
                  //Eliminar tareas del proyecto
                 
                  $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
 
                  foreach ($tareas as $tarea) {
                      $tarea->eliminar();
                  }

                  //eliminar proyecto
                  $proyecto->eliminar();

                  // Redireccionar
                  header('Location: /dashboard');
              }
          }
        }

          

          
      }
    }



