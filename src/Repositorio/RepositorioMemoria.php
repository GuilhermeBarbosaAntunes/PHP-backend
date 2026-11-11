<?php

namespace App\Repositorio;

use App\Modelo\Usuario;

/**
 * Interface implementation that stores data in MEMORY (array).
 *
 * "implements RepositorioInterface" = "implements Repositorio" in TS.
 * If a required method is missing, PHP reports an error.
 */
final class RepositorioMemoria implements RepositorioInterface
{
    /**
     * Private associative array - like a Map<string, Usuario>.
     * The key is the user's ID (string).
     *
     * In PHP, this is a typed property: private array $usuarios = [];
     */
    private array $usuarios = [];

    public function buscar(int $id): ?Usuario
    {
        // isset() = "does this key exist?" (like a JS object truthiness check)
        if (!isset($this->usuarios[$id])) {
            return null;   // no Optional<Usuario>; just null
        }

        return $this->usuarios[$id];
    }

    public function buscarTodos(): array
    {
        // array_values() reindexes the array (0,1,2...), like Object.values()
        return array_values($this->usuarios);
    }

    public function salvar(Usuario $usuario): bool
    {
        $this->usuarios[$usuario->id] = $usuario;

        return true;
    }
}
