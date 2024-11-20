<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

class enviarMail {
    private $mail;
    private $host;
    private $SMTPAuth;
    private $Username;
    private $Password;
    private $SMTPSecure;
    private $Port;
    private $mailFrom;
    private $mailSender;

    function __construct() {
        $this->mail = new PHPMailer(true);
        $this->host = "smtp.gmail.com";
        $this->SMTPAuth = true;
        $this->Username = "gabriela.contreras@est.fi.uncoma.edu.ar";
        // IMPORTANTE: Genera una contraseña de aplicación en Gmail
        $this->Password = "jrxy shrm lbqr icpm"; 
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->Port = 587;
        
        $this->mailFrom = "gabriela.contreras@est.fi.uncoma.edu.ar";
        $this->mailSender = "Rojo Carmesi";
    }

    public function newEmail($mailFrom="", $mailSender="", $mailFor="", $mailRecipientName="", $mailSubject="", $mailBody="") {
        try {
            // Configuración del servidor
            $this->mail->SMTPDebug = 0; // 0 para producción, 2 para debug
            $this->mail->isSMTP();
            $this->mail->Host = $this->host;
            $this->mail->SMTPAuth = $this->SMTPAuth;
            $this->mail->Username = $this->Username;
            $this->mail->Password = $this->Password;
            $this->mail->SMTPSecure = $this->SMTPSecure;
            $this->mail->Port = $this->Port;
            $this->mail->CharSet = 'UTF-8';

            // Limpiamos destinatarios previos
            $this->mail->clearAddresses();
            $this->mail->clearReplyTos();

            // Configuración del correo
            $mailFrom = ($mailFrom == "" || empty($mailFrom)) ? $this->mailFrom : $mailFrom;
            $mailSender = ($mailSender == "" || empty($mailSender)) ? $this->mailSender : $mailSender;

            $this->mail->setFrom($mailFrom, $mailSender);
            $mailFor = ($mailFor == "" || empty($mailFor)) ? "gabriela.contreras@est.fi.uncoma.edu.ar" : $mailFor;
            $this->mail->addAddress($mailFor, $mailRecipientName);
            $this->mail->addReplyTo($mailFrom, $mailSender);

            // Contenido
            $this->mail->isHTML(true);
            $this->mail->Subject = $mailSubject;
            $this->mail->Body = $mailBody;
            $this->mail->AltBody = strip_tags($mailBody);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return "Error al enviar el correo: {$this->mail->ErrorInfo}";
        }
    }
}