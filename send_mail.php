<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del formulario
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Datos del correo
    $to = "alexisortega236@gmail.com";
    $subject = "Nuevo mensaje de contacto";
    $body = "
    <html>
    <head>
        <title>Nuevo Mensaje de Contacto</title>
    </head>
    <body>
        <h2>Detalles del mensaje:</h2>
        <p><strong>Nombre Completo:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Teléfono:</strong> $phone</p>
        <p><strong>Mensaje:</strong></p>
        <p>$message</p>
    </body>
    </html>
    ";

    // Encabezados del correo
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: <$email>" . "\r\n";

    // Intentar enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        echo "<p>Mensaje enviado con éxito.</p>";
    } else {
        echo "<p>Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.</p>";
    }
} else {
    echo "<p>Acceso no autorizado.</p>";
}
?>
