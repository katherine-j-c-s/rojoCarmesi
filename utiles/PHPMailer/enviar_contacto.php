<?php
// enviar_contacto.php
require_once 'enviaMail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Crear el cuerpo del mensaje HTML
    $mailBody = "
    <h2>Nuevo mensaje de contacto</h2>
    <p><strong>Nombre:</strong> {$name}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Teléfono:</strong> {$phone}</p>
    <p><strong>Mensaje:</strong></p>
    <p>{$message}</p>
    ";
    
    $mailer = new enviarMail();
    $resultado = $mailer->newEmail(
         $email, // mailFrom (usará el default)
        $name, // mailSender (usará el default)
        "gabriela.contreras@est.fi.uncoma.edu.ar", // mailFor
        "Formulario de Contacto", // mailRecipientName
        "Nuevo mensaje de contacto - Rojo Carmesi", // mailSubject
        $mailBody // mailBody
    );
    
    if ($resultado === true) {
        echo "<script>
            alert('Mensaje enviado correctamente');
            window.location.href = '../../vista/home/index.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al enviar el mensaje: " . addslashes($resultado) . "');
            window.location.href = '../../vista/home/index.php';
        </script>";
    }
}