<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

function getLolVersion() {
    $resultado = file_get_contents($_ENV['DDRAGON_VERSIONS'], false);
    if ($resultado != false) {
        $json = \json_decode($resultado);
        $_ENV['LOL_PATCH'] = $json[0];
        $now = new DateTime("now");
        $_ENV['LAST_RELOAD'] = $now;
    }
}

if (!isset($_ENV['LOL_PATCH'])) { // isset env var || (last reload < today at 17h && it's >= 17h)?
    getLolVersion();
} else {
    $today = new DateTime("now");
    $today->setTime(17, 0);
    $now = new DateTime("now");
    if ($_ENV['LAST_RELOAD'] < $today && $now >= $today) {
        getLolVersion();
    }
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
