<?php

namespace App\Models;

/**
 * DTO (data transfer object) - the equivalent of a TypeScript "shape".
 *
 * In TS, you might write:  type Usuario = { id: number; nome: string; ... }
 * In PHP, we use a CLASS to get real types.
 *
 * New concepts for you (PHP 8+):
 * - "constructor promotion": declares and types properties directly in the constructor
 *   (instead of declaring each field separately as in TS classes)
 * - "readonly": like readonly in TS - it cannot be changed after creation
 */
final class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        // "?string" = "string | null" in TS. It can be omitted.
        public readonly ?string $email = null,
    ) {
    }

    /**
     * Method with parameter and return types, just like in TS:
     *   present(): string
     */
    public function present(): string
    {
        // Interpolation: double quotes interpolate variables (like JS template strings)
        // "??" is the null coalescing operator, just like in JS/TS
        $emailInfo = $this->email ?? 'yours e-mail';

        return "{$this->name} (id {$this->id}) — {$emailInfo}";
    }

    /**
     * Converts to an associative array (like .toJSON() / object spread in JS)
     */
    public function paraArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }
}
