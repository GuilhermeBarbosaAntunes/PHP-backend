<?php

namespace App\Repository;

use App\Models\User;

/**
 * INTERFACE - almost identical to TypeScript.
 *
 * TS:                                PHP:
 * interface Repository {            interface Repository {
 *   buscar(id: number): Usuario;       public function buscar(int $id): Usuario;
 * }                                  }
 *
 * The only difference: "public" is required for interface methods.
 *
 * Any class implementing this interface MUST have these methods
 * with these signatures - a contract just like in TS.
 */
interface RepositoryInterface
{
    public function search(int $id): ?User;   // may not find a match -> nullable

    /** @return User[] (docblock: PHPStan/IDE understands "array of User") */
    public function searchAll(): array;

    public function save(User $user): bool;
}
