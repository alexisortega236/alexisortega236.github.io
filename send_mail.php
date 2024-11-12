<?php
// Configuración de headers para caracteres especiales
header('Content-Type: text/html; charset=UTF-8');

// Función para limpiar y validar datos de entrada
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Inicializar array de respuesta
$response = array(
    'success' => false,
    'message' => ''
);

// Verificar si es una petición POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar datos del formulario
    $fullName = sanitizeInput($_POST['fullName']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $message = sanitizeInput($_POST['message']);
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "El formato del correo electrónico no es válido.";
        echo json_encode($response);
        exit;
    }
    
    // Configurar destinatario y asunto
    $to = "alexisortega236@gmail.com"; // Cambia esto por tu correo
    $subject = "Nuevo mensaje de contacto de $fullName";
    
    // Construir el cuerpo del correo
    $email_content = "
    <html>
    <head>
        <title>Nuevo mensaje de contacto</title>
    </head>
    <body>
        <h2>Detalles del mensaje:</h2>
        <p><strong>Nombre:</strong> $fullName</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Teléfono:</strong> $phone</p>
        <h3>Mensaje:</h3>
        <p>$message</p>
    </body>
    </html>
    ";
    
    // Configurar headers del correo
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: alexisortega236@gmail.com',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion()
    );
    
    // Intentar enviar el correo
    try {
        if(mail($to, $subject, $email_content, implode("\r\n", $headers))) {
            $response['success'] = true;
            $response['message'] = "¡Mensaje enviado con éxito! Gracias por contactarnos.";
        } else {
            throw new Exception("Error al enviar el mensaje.");
        }
    } catch (Exception $e) {
        $response['message'] = "Lo sentimos, hubo un error al enviar el mensaje. Por favor, inténtelo más tarde.";
    }
    
} else {
    $response['message'] = "Método de solicitud no válido.";
}

// Devolver respuesta como JSON
echo json_encode($response);
?>