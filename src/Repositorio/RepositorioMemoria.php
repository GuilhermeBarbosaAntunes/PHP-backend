<?php

namespace App\Repositorio;

use App\Modelo\Usuario;

/**
 * Implementação da interface guardando dados em MEMÓRIA (array).
 *
 * "implements RepositorioInterface" = "implements Repositorio" do TS.
 * Se faltar algum método do contrato, o PHP acusa erro.
 */
final class RepositorioMemoria implements RepositorioInterface
{
    /**
     * Array associativo privado — como um Map<string, Usuario>.
     * A chave é o id do usuário (string).
     *
     * Em PHP, propriedade tipada: private array $usuarios = [];
     */
    private array $usuarios = [];

    public function buscar(int $id): ?Usuario
    {
        // isset() = "existe essa chave?" (truthy check de objeto do JS)
        if (!isset($this->usuarios[$id])) {
            return null;   // sem Optional<Usuario>, só null mesmo
        }

        return $this->usuarios[$id];
    }

    public function buscarTodos(): array
    {
        // array_values() reindexa o array (0,1,2...) — tipo o Object.values()
        return array_values($this->usuarios);
    }

    public function salvar(Usuario $usuario): bool
    {
        $this->usuarios[$usuario->id] = $usuario;

        return true;
    }
}
