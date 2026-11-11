<?php

namespace App\Servico;

use App\Modelo\Usuario;
use App\Repositorio\RepositorioInterface;

/**
 * Business logic layer.
 *
 * Notice that the constructor receives the INTERFACE, not the implementation.
 * This is "dependency injection" - like in NestJS/Angular: the caller decides
 * which repository to use, and the service does not know or care which one.
 */
final class ServicoUsuario
{
    public function __construct(
        private RepositorioInterface $repositorio,
    ) {
    }

    public function cadastrar(string $nome, ?string $email = null): Usuario
    {
        // trim() and strlen() also exist in JS - the same idea
        $nome = trim($nome);

        // Validation with STRICT comparison (===) - just like in TS
        if ($nome === '') {
            throw new \InvalidArgumentException('O nome é obrigatório.');
        }

        if ($email !== null && trim($email) === '') {
            throw new \InvalidArgumentException('E-mail não pode ser só espaço.');
        }

        // Generate a simple ID: repository count + 1
        $novoId = count($this->repositorio->buscarTodos()) + 1;

        $usuario = new Usuario(
            id: $novoId,          // named arguments, similar to a JS object literal
            nome: $nome,
            email: $email,
        );

        $this->repositorio->salvar($usuario);

        return $usuario;
    }

    /** @return Usuario[] */
    public function listarTodos(): array
    {
        return $this->repositorio->buscarTodos();
    }

    public function buscarPorId(int $id): ?Usuario
    {
        return $this->repositorio->buscar($id);
    }
}
