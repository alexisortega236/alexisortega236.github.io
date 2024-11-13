<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Load Composer's autoloader
require 'vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                      // Disable debug output for production
        $mail->isSMTP();                           // Send using SMTP
        $mail->Host       = 'smtp.titan.email';    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                  // Enable SMTP authentication
        $mail->Username   = 'contaco@legalrentmx.com';  // SMTP username
        $mail->Password   = 'ma.i1LAWs(';          // SMTP password
        $mail->SMTPSecure = 'tls';                 // Enable TLS encryption
        $mail->Port       = 587;                   // Use port 587 with TLS

        // Recipients
        $mail->setFrom('contaco@legalrentmx.com', 'Legalrent');
        $mail->addAddress($email, $fullName);      // Use the email and name from the form
        $mail->addBCC('legalrentmx.com@gmail.com');  // Blind carbon copy to the admin email

        // Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = 'Gracias por contactarnos - Legalrent';

        // HTML Content
        $mail->Body = '
        <table class="email-container" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; line-height: 1.6; color: #737171;">
            <tr>
                <td class="header" style="background-color: #214757; color: white; padding: 20px; text-align: center; font-size: 24px;">
                    ¡Gracias por contactarnos!
                </td>
            </tr>
            <tr>
                <td class="body-content" style="padding: 20px; background-color: #f4f4f4;">
                    <h2 style="margin-top: 0; color: #214757;">Estimado/a ' . htmlspecialchars($fullName) . ',</h2>
                    <p>Gracias por ponerte en contacto con nosotros. En breve un agente se comunicará contigo para atender tu solicitud.</p>
                    <p>A continuación, te confirmamos los datos que nos proporcionaste:</p>
                    <ul style="list-style-type: none; padding-left: 0;">
                        <li><strong>Nombre:</strong> ' . htmlspecialchars($fullName) . '</li>
                        <li><strong>Email:</strong> ' . htmlspecialchars($email) . '</li>
                        <li><strong>Teléfono:</strong> ' . htmlspecialchars($phone) . '</li>
                        <li><strong>Mensaje:</strong> ' . htmlspecialchars($message) . '</li>
                    </ul>
                    <p>Si tienes alguna otra pregunta, no dudes en contactarnos.</p>
                    <a href="https://www.tu-sitio-web.com" class="button" style="display: inline-block; padding: 10px 15px; font-size: 16px; color: white; background-color: #214757; text-align: center; text-decoration: none; margin: 10px 0; border-radius: 5px;">Visita nuestro sitio web</a>
                </td>
            </tr>
            <tr>
                <td class="footer" style="background-color: #050707; color: white; text-align: center; padding: 10px; font-size: 12px;">
                    &copy; ' . date('Y') . ' Legalrent. Todos los derechos reservados.
                </td>
            </tr>
        </table>
        ';

        // Fallback content for non-HTML email clients
        $mail->AltBody = 'Gracias por ponerte en contacto con nosotros. En breve un agente se comunicará contigo.';

        // Send the email
        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no se pudo enviar. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "No se recibieron datos del formulario.";
}