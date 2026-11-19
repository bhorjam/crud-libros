<?php
declare(strict_types=1);

if (!function_exists('e')) {
    function e(mixed $valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('base_path')) {
    function base_path(): string
    {
        static $base = null;
        if ($base !== null) {
            return $base;
        }

        $configurada = App\Support\Config::get('base_path');
        if (is_string($configurada) && $configurada !== '') {
            return $base = '/' . trim($configurada, '/');
        }

        $raiz    = realpath(APP_ROOT);
        $docRoot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));

        if ($raiz !== false && $docRoot !== false && str_starts_with((string) $raiz, (string) $docRoot)) {
            $relativa = str_replace('\\', '/', substr((string) $raiz, strlen((string) $docRoot)));

            return $base = rtrim($relativa, '/');
        }

        return $base = '';
    }
}

if (!function_exists('url')) {
    function url(string $ruta = ''): string
    {
        return base_path() . '/' . ltrim($ruta, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $ruta): string
    {
        return url('public/' . ltrim($ruta, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $ruta): never
    {
        header('Location: ' . url($ruta), true, 302);
        exit;
    }
}

if (!function_exists('flash')) {
    function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['flash'] = ['tipo' => $tipo, 'msg' => $mensaje];
    }
}

if (!function_exists('pull_flash')) {
    function pull_flash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return is_array($flash) && isset($flash['msg']) ? $flash : null;
    }
}

if (!function_exists('campo_invalido')) {
    function campo_invalido(array $errores, string $campo): string
    {
        return isset($errores[$campo]) ? ' is-invalid' : '';
    }
}

if (!function_exists('mensaje_error')) {
    function mensaje_error(array $errores, string $campo): string
    {
        if (!isset($errores[$campo])) {
            return '';
        }

        return '<span class="invalid-feedback">'
            . '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"'
            . ' stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'
            . '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/>'
            . '<path d="M12 9v4"/><path d="M12 17h.01"/></svg>'
            . e($errores[$campo])
            . '</span>';
    }
}