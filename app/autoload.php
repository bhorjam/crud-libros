<?php
declare(strict_types=1);

namespace App\Support;

spl_autoload_register(static function (string $clase): void {
    $prefijo = 'App\\';
    if (!str_starts_with($clase, $prefijo)) {
        return;
    }

    $relativa = substr($clase, strlen($prefijo));
    $ruta     = APP_ROOT . '/app/' . str_replace('\\', '/', $relativa) . '.php';

    if (is_file($ruta)) {
        require $ruta;
    }
});

require_once __DIR__ . '/Support/helpers.php';