<?php

namespace App\Repository;

use App\Models\User;

/**
 * Interface implementation that stores data in MEMORY (array).
 *
 * "implements RepositoryInterface" = "implements Repositorio" in TS.
 * If a required method is missing, PHP reports an error.
 */
final class MemoryRepository implements RepositoryInterface
{
    /**
     * Private associative array - like a Map<string, User>.
     * The key is the user's ID (string).
     *
     * In PHP, this is a typed property: private array $users = [];
     */
    private array $users = [];

    public function search(int $id): ?User
    {
        // isset() = "does this key exist?" (like a JS object truthiness check)
        if (!isset($this->users[$id])) {
            return null;   // no Optional<User>; just null
        }

        return $this->users[$id];
    }

    public function searchAll(): array
    {
        // array_values() reindexes the array (0,1,2...), like Object.values()
        return array_values($this->users);
    }

    public function save(User $user): bool
    {
        $this->users[$user->id] = $user;

        return true;
    }
}
