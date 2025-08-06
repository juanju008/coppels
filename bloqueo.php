<?php
if (!function_exists('obtenerIPreal')) {
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
}

$ip = obtenerIPreal();
$lista_ips = file('../ips_bloqueadas.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (in_array($ip, $lista_ips)) {
    header('Location: acceso-denegado.html');
    exit();
}
?>