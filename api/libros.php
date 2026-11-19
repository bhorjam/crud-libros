<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/app/autoload.php';

use App\Core\App;
use App\Models\Categoria;
use App\Models\Libro;
use App\Models\LibroInput;

header('Content-Type: application/json; charset=utf-8');

App::iniciar(conSesion: false);

try {
    $libro = new Libro();

    $libros = $libro->listar(
        trim((string) ($_GET['q'] ?? '')),
        trim((string) ($_GET['estado'] ?? '')),
        (int) filter_var($_GET['cat'] ?? 0, FILTER_VALIDATE_INT),
        (string) ($_GET['orden'] ?? 'titulo'),
        (string) ($_GET['dir'] ?? 'asc')
    );

    echo json_encode([
        'libros'     => $libros,
        'resumen'    => $libro->resumen(),
        'categorias' => (new Categoria())->listarTodas(),
        'estados'    => LibroInput::ESTADOS,
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    error_log('[libreria:api] ' . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo cargar el catálogo.'], JSON_UNESCAPED_UNICODE);
}