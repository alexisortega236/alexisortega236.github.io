<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['fullName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars($_POST['phone']);
    $mensaje = htmlspecialchars($_POST['message']);

    $to = "alexisortega236@gmail.com"; // Cambiar por tu correo.
    $subject = "Nuevo mensaje de contacto";
    $body = "Nombre: $nombre\nCorreo: $email\nTeléfono: $telefono\nMensaje:\n$mensaje";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["success" => true, "message" => "Correo enviado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Hubo un error al enviar el correo."]);
    }
} else {
    http_response_code(405); // Método no permitido.
}
?>
