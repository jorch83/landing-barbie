<?php
// =============================================
// debug-geo.php — Solo para pruebas, borrar después
// =============================================

$api_key = 'e866fcdfc8276cf8edc564c27ecce687';

function obtener_ip_real() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) return $_SERVER['HTTP_CF_CONNECTING_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

$ip      = obtener_ip_real();
$url_api = "https://api.ipstack.com/{$ip}?access_key={$api_key}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
curl_close($ch);

$datos = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Debug Geolocalización</title>
    <style>
        body { font-family: monospace; background: #0f0f0f; color: #00ff88; padding: 40px; }
        h2   { color: #ffffff; }
        .card { background: #1a1a1a; border: 1px solid #00ff88; border-radius: 8px; padding: 20px; max-width: 500px; }
        .row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #222; }
        .label { color: #888; }
        .value { color: #00ff88; font-weight: bold; }
        .warn  { color: #ff4444; margin-top: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <h2>📍 Debug de Geolocalización</h2>
    <div class="card">
        <div class="row"><span class="label">IP detectada</span>       <span class="value"><?= $datos['ip'] ?? $ip ?></span></div>
        <div class="row"><span class="label">País (code)</span>        <span class="value"><?= $datos['country_code'] ?? '—' ?></span></div>
        <div class="row"><span class="label">País (nombre)</span>      <span class="value"><?= $datos['country_name'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Región</span>             <span class="value"><?= $datos['region_name'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Ciudad</span>             <span class="value"><?= $datos['city'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Código postal</span>      <span class="value"><?= $datos['zip'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Latitud</span>            <span class="value"><?= $datos['latitude'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Longitud</span>           <span class="value"><?= $datos['longitude'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Zona horaria</span>       <span class="value"><?= $datos['time_zone']['id'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Moneda</span>             <span class="value"><?= $datos['currency']['code'] ?? '—' ?></span></div>
        <div class="row"><span class="label">Idioma</span>             <span class="value"><?= $datos['location']['languages'][0]['name'] ?? '—' ?></span></div>
    </div>
    <p class="warn">⚠️ Este archivo es solo para pruebas. ¡Borrar antes de lanzar a producción!</p>
</body>
</html>