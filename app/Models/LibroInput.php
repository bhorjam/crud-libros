<?php
declare(strict_types=1);

namespace App\Models;

final class LibroInput
{
    public const ESTADOS = ['disponible', 'agotado', 'reservado'];

    public const MAX_TITULO    = 150;
    public const MAX_AUTOR     = 100;
    public const MAX_EDITORIAL = 100;
    public const MAX_PRECIO    = 999999.99;
    public const MAX_STOCK     = 999999;
    public const ANIO_MINIMO   = 1000;

    public static function vacio(): array
    {
        return [
            'titulo'       => '',
            'autor'        => '',
            'editorial'    => '',
            'categoria_id' => '',
            'precio'       => '',
            'stock'        => '',
            'anio'         => (int) date('Y'),
            'estado'       => 'disponible',
        ];
    }

    public static function desdePost(array $origen): array
    {
        return [
            'titulo'       => trim((string) ($origen['titulo'] ?? '')),
            'autor'        => trim((string) ($origen['autor'] ?? '')),
            'editorial'    => trim((string) ($origen['editorial'] ?? '')),
            'categoria_id' => (int) ($origen['categoria_id'] ?? 0),
            'precio'       => trim((string) ($origen['precio'] ?? '')),
            'stock'        => trim((string) ($origen['stock'] ?? '')),
            'anio'         => (int) ($origen['anio'] ?? 0),
            'estado'       => trim((string) ($origen['estado'] ?? 'disponible')),
        ];
    }

    public static function validar(array $datos): array
    {
        $errores = [];

        $errores += self::textoObligatorio($datos, 'titulo', 'El título es obligatorio.', self::MAX_TITULO, 2);
        $errores += self::textoObligatorio($datos, 'autor', 'El autor es obligatorio.', self::MAX_AUTOR, 2);
        $errores += self::textoObligatorio($datos, 'editorial', 'La editorial es obligatoria.', self::MAX_EDITORIAL, 2);

        if ((int) ($datos['categoria_id'] ?? 0) <= 0) {
            $errores['categoria_id'] = 'Selecciona una categoría.';
        }

        $precio = $datos['precio'] ?? '';
        if (!is_numeric($precio)) {
            $errores['precio'] = 'Precio inválido.';
        } elseif ((float) $precio < 0 || (float) $precio > self::MAX_PRECIO) {
            $errores['precio'] = 'El precio debe estar entre 0 y ' . number_format(self::MAX_PRECIO, 2, '.', '') . '.';
        }

        $stock = (string) ($datos['stock'] ?? '');
        if (!ctype_digit($stock) || (int) $stock > self::MAX_STOCK) {
            $errores['stock'] = 'Stock inválido.';
        }

        $anio = (int) ($datos['anio'] ?? 0);
        if ($anio < self::ANIO_MINIMO || $anio > (int) date('Y') + 1) {
            $errores['anio'] = 'Año inválido.';
        }

        if (!in_array($datos['estado'] ?? '', self::ESTADOS, true)) {
            $errores['estado'] = 'Estado inválido.';
        }

        return $errores;
    }

    private static function textoObligatorio(
        array $datos,
        string $campo,
        string $mensajeObligatorio,
        int $maximo,
        int $minimo
    ): array {
        $valor = trim((string) ($datos[$campo] ?? ''));
        $largo = mb_strlen($valor);

        if ($largo === 0) {
            return [$campo => $mensajeObligatorio];
        }
        if ($largo < $minimo) {
            return [$campo => 'Debe tener al menos ' . $minimo . ' caracteres.'];
        }
        if ($largo > $maximo) {
            return [$campo => 'No puede superar los ' . $maximo . ' caracteres.'];
        }

        return [];
    }
}