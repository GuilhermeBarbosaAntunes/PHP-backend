<?php

namespace App\Repositorio;

use App\Modelo\Usuario;

/**
 * INTERFACE — praticamente idêntica ao TypeScript.
 *
 * O TS:                              O PHP:
 * interface Repositorio {            interface Repositorio {
 *   buscar(id: number): Usuario;       public function buscar(int $id): Usuario;
 * }                                  }
 *
 * Só difere em: "public" é obrigatório nos métodos da interface.
 *
 * Quem implementar esta interface é OBRIGADO a ter esses métodos
 * com essas assinaturas — contrato igual ao do TS.
 */
interface RepositorioInterface
{
    public function buscar(int $id): ?Usuario;   // pode não encontrar → nullable

    /** @return Usuario[]  (docblock: o PHPStan/IDE entende "array de Usuario") */
    public function buscarTodos(): array;

    public function salvar(Usuario $usuario): bool;
}
