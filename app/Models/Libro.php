<?php
declare(strict_types=1);

namespace App\Models;

use App\Support\Database;
use PDO;

final class Libro
{
    private const COLUMNAS_ORDEN = ['titulo', 'autor', 'precio', 'stock', 'anio'];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conexion();
    }

    public function listar(
        string $buscar = '',
        string $estado = '',
        int $categoriaId = 0,
        string $orden = 'titulo',
        string $direccion = 'asc'
    ): array {
        $orden     = in_array($orden, self::COLUMNAS_ORDEN, true) ? $orden : 'titulo';
        $direccion = strtolower($direccion) === 'desc' ? 'DESC' : 'ASC';

        $condiciones = [];
        $parametros  = [];

        if ($buscar !== '') {
            $condiciones[] = '(l.titulo LIKE :buscar_titulo OR l.autor LIKE :buscar_autor OR l.editorial LIKE :buscar_editorial)';
            $parametros['buscar_titulo']    = '%' . $buscar . '%';
            $parametros['buscar_autor']     = '%' . $buscar . '%';
            $parametros['buscar_editorial'] = '%' . $buscar . '%';
        }

        if (in_array($estado, LibroInput::ESTADOS, true)) {
            $condiciones[] = 'l.estado = :estado';
            $parametros['estado'] = $estado;
        }

        if ($categoriaId > 0) {
            $condiciones[] = 'l.categoria_id = :categoria';
            $parametros['categoria'] = $categoriaId;
        }

        $sql = 'SELECT l.*, c.nombre AS categoria FROM libros l JOIN categorias c ON c.id = l.categoria_id';
        if ($condiciones !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $condiciones);
        }
        $sql .= " ORDER BY l.{$orden} {$direccion}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql  = 'SELECT l.*, c.nombre AS categoria FROM libros l JOIN categorias c ON c.id = l.categoria_id WHERE l.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function crear(array $datos): int
    {
        $sql = 'INSERT INTO libros (titulo, autor, editorial, categoria_id, precio, stock, anio, estado)
                VALUES (:titulo, :autor, :editorial, :categoria, :precio, :stock, :anio, :estado)';

        $this->db->prepare($sql)->execute($this->parametros($datos));

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(int $id, array $datos): bool
    {
        $sql = 'UPDATE libros
                   SET titulo = :titulo, autor = :autor, editorial = :editorial,
                       categoria_id = :categoria, precio = :precio, stock = :stock,
                       anio = :anio, estado = :estado
                 WHERE id = :id';

        $parametros = $this->parametros($datos) + ['id' => $id];

        $this->db->prepare($sql)->execute($parametros);

        return true;
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM libros WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function resumen(): array
    {
        $sql = "SELECT COUNT(*) AS total,
                       COALESCE(SUM(stock), 0) AS total_stock,
                       COALESCE(SUM(estado = 'disponible'), 0) AS disponibles,
                       COALESCE(SUM(estado = 'agotado'), 0) AS agotados
                  FROM libros";

        $fila = $this->db->query($sql)->fetch() ?: [];

        return [
            'total'       => (int) ($fila['total'] ?? 0),
            'total_stock' => (int) ($fila['total_stock'] ?? 0),
            'disponibles' => (int) ($fila['disponibles'] ?? 0),
            'agotados'    => (int) ($fila['agotados'] ?? 0),
        ];
    }

    private function parametros(array $datos): array
    {
        return [
            'titulo'    => $datos['titulo'],
            'autor'     => $datos['autor'],
            'editorial' => $datos['editorial'],
            'categoria' => (int) $datos['categoria_id'],
            'precio'    => (float) $datos['precio'],
            'stock'     => (int) $datos['stock'],
            'anio'      => (int) $datos['anio'],
            'estado'    => $datos['estado'],
        ];
    }
}
