<?php
function obtener_ip_real() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }

    return $_SERVER['REMOTE_ADDR'] ?? '';
}

// Verificar cookie para no gastar peticiones
if (isset($_COOKIE['user_country'])) {
    $pais = $_COOKIE['user_country'];
} else {
    $ip = obtener_ip_real();

    if ($ip === '127.0.0.1' || $ip === '::1' || empty($ip)) {
        $ip = '201.141.128.1'; // IP de prueba MX
    }

    $api_key = 'e866fcdfc8276cf8edc564c27ecce687';
    $url_api = "https://api.ipstack.com/{$ip}?access_key={$api_key}&fields=country_code";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    $datos = json_decode($response, true);
    $pais = strtoupper($datos['country_code'] ?? 'MX');

    setcookie('user_country', $pais, time() + 86400, '/');
}

// Debug temporal
error_log('IP: ' . $ip . ' | Pais: ' . $pais . ' | URI: ' . ($_SERVER['REQUEST_URI'] ?? ''));

switch ($pais) {
    case 'AR':
        header('Location: /coleccion-barbie/coleccion-barbie-arg.html', true, 302);
        exit;

    case 'US':
        header('Location: /coleccion-barbie/coleccion-barbie-eng.html', true, 302);
        exit;

    default:
        header('Location: /coleccion-barbie/coleccion-barbie-mx.html', true, 302);
        exit;
}
?>