<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
       
         $alertas=[];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //si no hay alertas verificar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);
                
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El usuario No existe o no esta confirmado');

                }else{
                    //en este momento el usuario existe y esta confirmado
                     if( password_verify($_POST['password'], $usuario->password)){
                          
                        //en caso de que el password sea correcto podemos iniciar sesion 
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        //redireccionar al usuario logueado
                        header('Location: /dashboard ');

                     }else{
                        Usuario::setAlerta('error', 'Password Incorrecto');
                     }
               
                }
                
            }
        }
              $alertas= Usuario::getAlertas();
        //Renderizar la vista de login
        $router->render('auth/login',[
           'titulo' => 'Iniciar Sesion',
           'alertas' => $alertas
        ]);
    }
    public static function logout(){
      session_start();
      $_SESSION = [];
      header('Location: /');

        
    }
    public static function crear(Router $router){
         $alertas = [];
          $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $usuario->sincronizar($_POST);
           $alertas = $usuario->validarNuevaCuenta();
          

           if(empty($alertas)){
            $existeUsuario = Usuario::where('email', $usuario->email);
        
            if($existeUsuario){
             Usuario::setAlerta('error', 'El usuario ya esta registrado');
             $alertas = Usuario::getAlertas();
            }else{
              //Hashear el password
              $usuario->hashPassword();
              //eliminar el passwrod2 por que no se requiere en la BD
              unset($usuario->password2);
              //generar el token
              $usuario->crearToken();
                //crear un nuevo usuaario
         $resultado =  $usuario->guardar();

         //enviar email
         $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
         $email->enviarConfirmacion();


         if($resultado){
            header('Location: /mensaje');
         }
            }

           }

        }
          //Renderizar la vista de crear
          $router->render('auth/crear',[
            'titulo' => 'Crea tu cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
            
         ]);
    }
    public static function olvide(Router $router){
            
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
              $usuario = new Usuario($_POST);
              $alertas = $usuario->validarEmail();

              if(empty($alertas)){
                //Buscra el usuario

                $usuario = Usuario::where('email', $usuario->email);
            if($usuario && $usuario->confirmado){
                //encontre al usuario
             
                //generar un nuevo token
                $usuario->crearToken();
                unset($usuario->password2);

                //Actualizar el usuario
                $usuario->guardar();
                //Enviar el email 
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarInstrucciones();

                // Imprimir la alerta
                Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

                
            }else{
                Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                $alertas = Usuario::getAlertas();
            }
            
            }
        }
    $alertas = Usuario::getAlertas();
        //renderizar la vista de olvide mi password
        $router->render('auth/olvide', [
            'titulo' => 'Reestablecer password',
            'alertas' => $alertas
        ]);

    }
    public static function reestablecer(Router $router){
       
         $token = s($_GET['token']);
         $mostrar = true;
      
         if(!$token) header('Location: /');
          
         //identificar el usuario con este token

         $usuario = Usuario::where('token', $token);

         if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar=false; 
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
              //añadir el nuevo password

               if($usuario !== null){
                $usuario->sincronizar($_POST);
               } else{

                echo "el objeto es nulo";
               }

   

              //Validar el password
              
             $alertas= $usuario->validarPassword();
          
             if(empty($alertas)){
                //hashear el nuevo password
                  
                $usuario-> hashPassword();
                     
                //eliminar toquen
                  $usuario->token = null;

                //guardar usuario en la bd
                $resultado = $usuario->guardar();

                 //redireccionar
                   
                 if($resultado){
                    header('Location: /');
                 }
             }

        }
        
        $alertas = Usuario::getAlertas();
        //mostrar la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'añade un nuevo password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);

    }

    public static function mensaje(Router $router){
        
        $router->render('auth/mensaje', [
            'titulo' => 'añade un nuevo password'
        ]);


        
    }
    public static function confirmar(Router $router){
        $token = s($_GET['token']);
      //si no hay toquen mandamos al usuario a la pagina principal
       if(!$token) header('Location:/');
       //encontrar al usuario con ese token
       $usuario = Usuario::where('token', $token);

       if(empty($usuario)){
        Usuario::setAlerta('error', 'Token no valido');
       }else{
        //confirmar la cuentaa
        $usuario->confirmado =1;
        $usuario->token = null;
        unset($usuario->password2);
        //guardar en la base de datos
        $usuario->guardar();
        Usuario::setAlerta('exito', 'Token valido, puedes iniciar sesion');



       
       }
       $alertas =Usuario::getAlertas();
     
  
$router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta',
            'alertas' => $alertas
        ]);

        
    }
}