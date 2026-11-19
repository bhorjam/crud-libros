<?php
declare(strict_types=1);

namespace App\Core;


final class App
{
    public static function iniciar(bool $conSesion = true): void
    {
        date_default_timezone_set((string) (\App\Support\Config::get('timezone') ?? 'UTC'));

        self::cabecerasSeguridad();

            if (!$conSesion) {
                return;
            }

        self::iniciarSesion();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function csrfToken(): string
    {
        return (string) ($_SESSION['csrf_token'] ?? '');
    }

    public static function csrfValido(?string $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals(self::csrfToken(), $token);
    }

    private static function cabecerasSeguridad(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-Frame-Options: DENY');
    }

    private static function iniciarSesion(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'httponly' => true,
            'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}