<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Models\Categoria;
use App\Models\Libro;
use App\Models\LibroInput;
use PDOException;
use Throwable;

final class LibroController
{
    private const VISTA_INDEX = 'libros/index.php';
    private const VISTA_FORM  = 'libros/form.php';

    private Libro $libro;
    private Categoria $categoria;

    public function __construct()
    {
        $this->libro     = new Libro();
        $this->categoria = new Categoria();
    }

    public function index(): void
    {
        $this->renderizar(self::VISTA_INDEX, [
            'resumen'     => $this->libro->resumen(),
            'categorias'  => $this->categoria->listarTodas(),
            'flash'       => pull_flash(),
            'pageTitle'   => 'Tu Libro a tu Alcance',
            'pageSection' => 'index',
        ]);
    }

    public function crear(): void
    {
        $this->renderizarFormulario(LibroInput::vacio(), []);
    }

    public function guardar(): void
    {
        $this->exigirPost();

        $datos   = LibroInput::desdePost($_POST);
        $errores = $this->validar($datos);

        if ($errores !== []) {
            $this->renderizarFormulario($datos, $errores);

            return;
        }

        try {
            $this->libro->crear($datos);
            flash('success', 'Libro "' . $datos['titulo'] . '" registrado.');
            redirect('index.php');
        } catch (PDOException $error) {
            $this->registrarError($error);
            $this->renderizarFormulario($datos, ['db' => 'No se pudo guardar el libro. Verifica los datos.']);
        }
    }

    public function editar(int $id): void
    {
        $datos = $this->libro->buscarPorId($id);

        if ($datos === null) {
            flash('error', 'El libro solicitado no existe.');
            redirect('index.php');
        }

        $this->renderizarFormulario($datos, [], true);
    }

    public function actualizar(int $id): void
    {
        $this->exigirPost();

        $original = $this->libro->buscarPorId($id);

        if ($original === null) {
            flash('error', 'El libro solicitado no existe.');
            redirect('index.php');
        }

        $datos   = LibroInput::desdePost($_POST) + ['id' => $original['id']];
        $errores = $this->validar($datos);

        if ($errores !== []) {
            $datos['created_at'] = $original['created_at'] ?? null;
            $this->renderizarFormulario($datos, $errores, true);

            return;
        }

        try {
            $this->libro->actualizar($id, $datos);
            flash('success', 'Libro "' . $datos['titulo'] . '" actualizado.');
            redirect('index.php');
        } catch (PDOException $error) {
            $this->registrarError($error);
            $datos['created_at'] = $original['created_at'] ?? null;
            $this->renderizarFormulario($datos, ['db' => 'No se pudo actualizar el libro. Verifica los datos.'], true);
        }
    }

    public function eliminar(int $id): void
    {
        $this->exigirPost();

        $libro = $this->libro->buscarPorId($id);

        if ($libro === null) {
            flash('error', 'El libro solicitado no existe.');
            redirect('index.php');
        }

        try {
            $this->libro->eliminar($id);
            flash('success', 'Libro "' . $libro['titulo'] . '" eliminado.');
        } catch (PDOException $error) {
            $this->registrarError($error);
            flash('error', 'No se pudo eliminar el libro.');
        }

        redirect('index.php');
    }

    private function validar(array $datos): array
    {
        $errores = LibroInput::validar($datos);

        $categoriaId = (int) ($datos['categoria_id'] ?? 0);
        if (!isset($errores['categoria_id']) && !$this->categoria->existe($categoriaId)) {
            $errores['categoria_id'] = 'La categoría seleccionada no existe.';
        }

        return $errores;
    }

    private function renderizarFormulario(array $datos, array $errores, bool $modoEditar = false): void
    {
        $this->renderizar(self::VISTA_FORM, [
            'datos'       => $datos,
            'errores'     => $errores,
            'modoEditar'  => $modoEditar && isset($datos['id']),
            'categorias'  => $this->categoria->listarTodas(),
            'pageTitle'   => ($modoEditar ? 'Editar' : 'Nuevo') . ' Libro — Tu Libro a tu Alcance',
            'pageSection' => 'form',
        ]);
    }

    private function renderizar(string $vista, array $variables): void
    {
        extract($variables, EXTR_SKIP);

        require APP_ROOT . '/app/Views/layout.php';
        require APP_ROOT . '/app/Views/' . $vista;
        require APP_ROOT . '/app/Views/partials/footer.php';
    }

    private function exigirPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            redirect('index.php');
        }

        $token = $_POST['csrf_token'] ?? null;
        if (!App::csrfValido(is_string($token) ? $token : null)) {
            http_response_code(403);
            exit('Solicitud no válida. Recarga la página e inténtalo de nuevo.');
        }
    }

    private function registrarError(Throwable $error): void
    {
        error_log('[libreria] ' . $error->getMessage());
    }
}