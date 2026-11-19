<?php
declare(strict_types=1);

namespace App\Core;


final class Router
{
    private array $rutas = [];

    public function add(string $accion, callable $manejador): self
    {
        $this->rutas[$accion] = $manejador;

        return $this;
    }

    public function despachar(string $accion): void
    {
        $manejador = $this->rutas[$accion] ?? $this->rutas['no_encontrado'] ?? null;

        if ($manejador === null) {
            http_response_code(404);
            exit('Página no encontrada.');
        }

        $manejador();
    }

    public function existe(string $accion): bool
    {
        return isset($this->rutas[$accion]);
    }
}