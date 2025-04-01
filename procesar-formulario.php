<?php
// 1. Validar el CAPTCHA matemático
$respuesta_usuario = (int)$_POST['captcha'];
$suma_real = (int)$_POST['suma_real'];

if ($respuesta_usuario !== $suma_real) {
    die('Error: La respuesta anti-spam es incorrecta. Por favor, inténtalo de nuevo.');
}

// 2. Recoger y sanitizar datos del formulario
$nombre = htmlspecialchars(trim($_POST['nombre']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : 'No proporcionado';
$asunto = htmlspecialchars(trim($_POST['asunto']));
$mensaje = htmlspecialchars(trim($_POST['mensaje']));

// 3. Validar campos obligatorios
if (empty($nombre) || empty($email) || empty($mensaje) || empty($asunto)) {
    die('Por favor completa todos los campos obligatorios.');
}

// 4. Configurar y enviar el correo
$destinatario = "electricidad-tayllefert@hotmail.com";
$asunto_correo = "Nuevo mensaje de contacto: $asunto";
$cuerpo_mensaje = "
    <h2>Nuevo mensaje de contacto</h2>
    <p><strong>Nombre:</strong> $nombre</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Teléfono:</strong> $telefono</p>
    <p><strong>Asunto:</strong> $asunto</p>
    <p><strong>Mensaje:</strong></p>
    <p>$mensaje</p>
";

// Cabeceras para correo HTML
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: $nombre <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

// 5. Enviar el correo
if (mail($destinatario, $asunto_correo, $cuerpo_mensaje, $headers)) {
    echo "¡Mensaje enviado con éxito! Nos pondremos en contacto pronto.";
} else {
    echo "Error al enviar el mensaje. Por favor inténtalo más tarde.";
}
?>
