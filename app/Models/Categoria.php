<?php
declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

final class Categoria
{
    public function listarTodas(): array
    {
        $sql = 'SELECT id, nombre FROM categorias ORDER BY nombre';

        return Database::conexion()->query($sql)->fetchAll();
    }

    public function existe(int $id): bool
    {
        $sql  = 'SELECT 1 FROM categorias WHERE id = :id LIMIT 1';
        $stmt = Database::conexion()->prepare($sql);
        $stmt->execute(['id' => $id]);

        return (bool) $stmt->fetchColumn();
    }
}
