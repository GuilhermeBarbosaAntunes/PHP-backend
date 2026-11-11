<?php

namespace App\Repositorio;

use App\Modelo\Usuario;

/**
 * INTERFACE - almost identical to TypeScript.
 *
 * TS:                                PHP:
 * interface Repositorio {            interface Repositorio {
 *   buscar(id: number): Usuario;       public function buscar(int $id): Usuario;
 * }                                  }
 *
 * The only difference: "public" is required for interface methods.
 *
 * Any class implementing this interface MUST have these methods
 * with these signatures - a contract just like in TS.
 */
interface RepositorioInterface
{
    public function buscar(int $id): ?Usuario;   // may not find a match -> nullable

    /** @return Usuario[] (docblock: PHPStan/IDE understands "array of Usuario") */
    public function buscarTodos(): array;

    public function salvar(Usuario $usuario): bool;
}
