<?php
header('Content-Type: text/html; charset=utf-8');

// 1. Validar campos obligatorios
$camposRequeridos = ['nombre', 'email', 'mensaje', 'g-recaptcha-response'];
foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo])) {
        die('Error: Por favor completa todos los campos obligatorios.');
    }
}

// 2. Validar reCAPTCHA
$recaptchaSecret = "6LeoLwYrAAAAAOFR6MtPexxW_W4vqYWqMdEtsc2";
$recaptchaResponse = $_POST['g-recaptcha-response'];

// Verificar con Google
$recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse";
$recaptcha = file_get_contents($recaptchaUrl);
$recaptcha = json_decode($recaptcha);

if (!$recaptcha->success) {
    die('Error: La verificación reCAPTCHA falló. Por favor inténtalo de nuevo.');
}

// 3. Recoger datos del formulario
$nombre = htmlspecialchars(trim($_POST['nombre']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : 'No proporcionado';
$asunto = htmlspecialchars(trim($_POST['asunto']));
$mensaje = htmlspecialchars(trim($_POST['mensaje']));
$destinatario = "electricidad-tayllefert@hotmail.com";

// 4. Construir el mensaje de correo
$asuntoCorreo = "Nuevo mensaje de contacto: $asunto";
$cuerpoMensaje = "
    <h2>Nuevo mensaje de contacto</h2>
    <p><strong>Nombre:</strong> $nombre</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Teléfono:</strong> $telefono</p>
    <p><strong>Asunto:</strong> $asunto</p>
    <p><strong>Mensaje:</strong></p>
    <p>$mensaje</p>
";

// 5. Configurar cabeceras para HTML
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: $nombre <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

// 6. Enviar el correo
if (mail($destinatario, $asuntoCorreo, $cuerpoMensaje, $headers)) {
    echo "¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.";
} else {
    echo "Error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.";
}
?>