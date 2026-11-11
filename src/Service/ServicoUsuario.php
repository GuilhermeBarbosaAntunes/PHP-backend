<?php

namespace App\Service;

use App\Models\User;
use App\Repository\RepositoryInterface;

/**
 * Business logic layer.
 *
 * Notice that the constructor receives the INTERFACE, not the implementation.
 * This is "dependency injection" - like in NestJS/Angular: the caller decides
 * which repository to use, and the service does not know or care which one.
 */
final class UserService
{
    public function __construct(
        private RepositoryInterface $repo,
    ) {
    }

    public function register(string $name, ?string $email = null): User
    {
        // trim() and strlen() also exist in JS - the same idea
        $name = trim($name);

        // Validation with STRICT comparison (===) - just like in TS
        if ($name === '') {
            throw new \InvalidArgumentException('name is required.');
        }

        if ($email !== null && trim($email) === '') {
            throw new \InvalidArgumentException('E-mail cannot be only whitespace.');
        }

        // Generate a simple ID: repo count + 1
        $newId = count($this->repo->searchAll()) + 1;

        $user = new User(
            id: $newId,          // named arguments, similar to a JS object literal
            name: $name,
            email: $email,
        );

        $this->repo->save($user);

        return $user;
    }

    /** @return User[] */
    public function listarTodos(): array
    {
        return $this->repo->searchAll();
    }

    public function searchById(int $id): ?User
    {
        return $this->repo->search($id);
    }
}
