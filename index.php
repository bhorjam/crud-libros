<?php
declare(strict_types=1);

define('APP_ROOT', __DIR__);

require APP_ROOT . '/app/autoload.php';

use App\Controllers\LibroController;
use App\Core\App;
use App\Core\Router;

set_exception_handler(static function (Throwable $error): void {
    error_log('[libreria] ' . $error->getMessage());

    if (!headers_sent()) {
        http_response_code(500);
    }

    echo 'Ocurrió un error interno. Revisa la configuración de la base de datos y los registros de PHP.';
});

App::iniciar();

$accion = is_string($_GET['action'] ?? null) ? $_GET['action'] : 'index';
$id     = (int) filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);

$controlador = new LibroController();

$router = (new Router())
    ->add('index',      [$controlador, 'index'])
    ->add('crear',      [$controlador, 'crear'])
    ->add('guardar',    [$controlador, 'guardar'])
    ->add('editar',     static fn () => $controlador->editar($id))
    ->add('actualizar', static fn () => $controlador->actualizar($id))
    ->add('eliminar',   static fn () => $controlador->eliminar($id))
    ->add('no_encontrado', static function (): void {
        http_response_code(404);
        echo 'Página no encontrada.';
    });

$router->despachar($accion);