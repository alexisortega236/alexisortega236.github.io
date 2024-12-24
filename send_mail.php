<?php
header('Content-Type: application/json'); // Especifica que la respuesta será JSON

require 'libs/PHPMailer/PHPMailer.php';
require 'libs/PHPMailer/SMTP.php';
require 'libs/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Respuesta inicial
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y sanitizar datos del formulario
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validar campos
    if (!$email) {
        $response['message'] = 'El correo electrónico ingresado no es válido.';
        echo json_encode($response);
        exit;
    }

    if (empty($fullName) || empty($phone) || empty($message)) {
        $response['message'] = 'Todos los campos son obligatorios.';
        echo json_encode($response);
        exit;
    }

    // Crear instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email'; // Servidor SMTP de Titan
        $mail->SMTPAuth = true;
        $mail->Username = 'contacto@juridicasa.com'; // Tu correo de remitente
        $mail->Password = 'ma.i1LAWs('; // Tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cifrado TLS
        $mail->Port = 587; // Puerto para TLS

        // **1. Enviar correo al administrador**
        $mail->setFrom('contacto@juridicasa.com', 'Landing Juridicasa'); // Remitente (tu correo)
        $mail->addAddress('contacto@juridicasa.com', 'Administrador'); // El destinatario principal (tú)
        $mail->addReplyTo($email, $fullName); // Para responder al usuario

        // Contenido del correo al administrador
        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje de contacto desde la landing page';
        $mail->Body = "<h1>Nuevo mensaje recibido</h1>
                       <p><strong>Nombre:</strong> {$fullName}</p>
                       <p><strong>Email:</strong> {$email}</p>
                       <p><strong>Teléfono:</strong> {$phone}</p>
                       <p><strong>Mensaje:</strong><br>{$message}</p>";
        $mail->AltBody = "Nuevo mensaje recibido\n\nNombre: {$fullName}\nEmail: {$email}\nTeléfono: {$phone}\nMensaje: {$message}";

        // Configuración de codificación UTF-8
        $mail->CharSet = 'UTF-8'; // Codificación de caracteres
        $mail->Encoding = 'base64'; // Opcional: Mejor manejo de caracteres extendidos

        // Enviar correo al administrador
        $mail->send();

        // **2. Enviar confirmación al usuario**
        $mail->clearAddresses(); // Limpiar los destinatarios actuales
        $mail->addAddress($email, $fullName); // Destinatario: el usuario que completó el formulario
        $mail->Subject = 'Confirmación: Hemos recibido tu mensaje';
        $mail->Body = "<h1>Gracias por contactarnos</h1>
                       <p>Hola, <strong>{$fullName}</strong>. Hemos recibido tu mensaje correctamente.</p>
                       <p>Uno de nuestros representantes se pondrá en contacto contigo pronto.</p>
                       <p><strong>Detalles de tu mensaje:</strong></p>
                       <ul>
                           <li><strong>Nombre:</strong> {$fullName}</li>
                           <li><strong>Email:</strong> {$email}</li>
                           <li><strong>Teléfono:</strong> {$phone}</li>
                           <li><strong>Mensaje:</strong> {$message}</li>
                       </ul>
                       <p>Gracias por confiar en nosotros.</p>";
        $mail->AltBody = "Gracias por contactarnos. Hemos recibido tu mensaje correctamente.\n\nDetalles de tu mensaje:\n
                          Nombre: {$fullName}\n
                          Email: {$email}\n
                          Teléfono: {$phone}\n
                          Mensaje: {$message}";

        // Enviar correo de confirmación al usuario
        $mail->send();

        // Respuesta exitosa
        $response['success'] = true;
        $response['message'] = 'El mensaje fue enviado correctamente y se envió una confirmación al usuario.';
    } catch (Exception $e) {
        // Manejo de errores y logging
        $response['message'] = "Error al enviar el correo: {$mail->ErrorInfo}";
        error_log("Error en PHPMailer: {$mail->ErrorInfo}", 3, __DIR__ . '/logs/errors.log');
    }
} else {
    $response['message'] = 'Acceso no permitido.';
}

// Responder con JSON
echo json_encode($response);
