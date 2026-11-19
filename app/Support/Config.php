<?php
declare(strict_types=1);

namespace App\Support;

final class Config
{
    private static ?array $cache = null;
    private static array $secciones = [];

    public static function get(string $clave, mixed $defecto = null): mixed
    {
        return self::cargar()[$clave] ?? $defecto;
    }

    public static function seccion(string $nombre): array
    {
        self::cargar();
        $seccion = self::$secciones[$nombre] ?? [];

        return is_array($seccion) ? $seccion : [];
    }

    private static function cargar(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $config = [];
        self::$secciones = [];

        foreach (['app', 'database'] as $archivo) {
            $ruta  = APP_ROOT . '/config/' . $archivo . '.php';
            $datos = is_file($ruta) ? require $ruta : [];
            $datos = is_array($datos) ? $datos : [];

            self::$secciones[$archivo] = $datos;
            $config = array_merge($config, $datos);
        }

        return self::$cache = $config;
    }
}