<?php
/*
 * 
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'resourses/PHPMailer-6.5.0/src/Exception.php';
require 'resourses/PHPMailer-6.5.0/src/PHPMailer.php';
require 'resourses/PHPMailer-6.5.0/src/SMTP.php';
$email = $_REQUEST['email'];
$sender = $_REQUEST['emailSender'];
$password = $_REQUEST['password'];




// Адрес самой почты и имя отправителя
$mail = new PHPMailer(true);
$mail->SMTPDebug = 0;                                        
$mail->isSMTP(); 
$mail->Host       = 'smtp.mail.ru;';                     
$mail->SMTPAuth   = true;                              
$mail->Username   = $sender;                  
$mail->Password   = $password;                         
$mail->SMTPSecure = 'tls';                               
$mail->Port       = 587;   

$mail->setFrom($email, 'DearAdmin');
$mail->addAddress($email, 'DearAdmin'); 
$mail->Subject = 'Simtech Student';
$mail->msgHTML("Сообщение со вложением csv");
$mail->addAttachment('messagetoEmail.csv');



try {
    $mail->send();
    echo('Seucess Send To '.$email);
} catch(Exeption $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}