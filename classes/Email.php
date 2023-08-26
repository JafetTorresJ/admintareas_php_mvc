<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {

    $this->email = $email;
    $this->nombre = $nombre;
    $this->token = $token;


    }

    public function enviarConfirmacion()

    {
        //crear el objeto del email
 $mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = $_ENV['EMAIL_HOST'];
$mail->SMTPAuth = true;
$mail->Port = $_ENV['EMAIL_PORT'];
$mail->Username = $_ENV['EMAIL_USER']; 
$mail->Password = $_ENV['EMAIL_PASS'];

$mail->setFrom('cuentas@Uptask.com', 'Uptask.com');
$mail->addAddress($this->email);
$mail->Subject = 'Confirma tu cuenta';

//Set html
$mail->isHTML(TRUE);
$mail->CharSet = 'UTF-8';

$contenido = "<html>";
$contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en Uptask Jafet, solo debes cofirmarla presionando el siguiente enlace </p>";
$contenido .= "<p>Presiona aqui : <a href='" . $_ENV['APP_URL'] . "/confirmar?token=". $this->token . "'>Confirmar Cuenta</a> </p>";
$contenido .= "<p> Si tu no solicitaste esta cuenta puedes ignorar el mensaje </p>";
$contenido .= "</html>";
$mail->Body = $contenido;

//enviar el email

$mail->send();


    }


    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER']; 
        $mail->Password = $_ENV['EMAIL_PASS'];
        

$mail->setFrom('cuentas@upTask.com', 'UpTask.com');
$mail->addAddress($this->email);
$mail->Subject = 'Reestablece tu password';

//Set html
$mail->isHTML(TRUE);
$mail->CharSet = 'UTF-8';

$contenido = "<html>";
$contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado cambiar tu password, sigue el siguiente enlace para hacerlo </p>";
$contenido .= "<p>Presiona aqui : <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=". $this->token . "'>Reestablecer password</a> </p>";
$contenido .= "<p> Si tu no solicitaste este cambio puedes ignorar el mensaje </p>";
$contenido .= "</html>";
$mail->Body = $contenido;

$mail->send();
        
    }


}