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
    // Get the recipient's email from the form
    $destinatario = $_POST['email'];

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
        
        // Use the dynamic email address
        if (!empty($destinatario)) {
            $mail->addAddress($destinatario);  // Use the variable for the recipient's email
        } else {
            throw new Exception('No se proporcion贸 un correo electr贸nico v谩lido.');
        }

        $mail->addBCC('alexisortega236@gmail.com'); // Opcional: agrega un BCC para copias ocultas

        // Attachments (if necessary)
        // $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

        // Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = 'Test';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no se pudo enviar. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "No se recibieron datos del formulario.";
}
