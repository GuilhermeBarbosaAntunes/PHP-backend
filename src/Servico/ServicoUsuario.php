<?php

namespace App\Servico;

use App\Modelo\Usuario;
use App\Repositorio\RepositorioInterface;

/**
 * Camada de regra de negócio.
 *
 * Repare no construtor: ele recebe a INTERFACE, não a implementação.
 * Isso é "injeção de dependência" — igual ao NestJS/Angular: quem chama
 * decide qual repositório usar, e o serviço não sabe nem se importa.
 */
final class ServicoUsuario
{
    public function __construct(
        private RepositorioInterface $repositorio,
    ) {
    }

    public function cadastrar(string $nome, ?string $email = null): Usuario
    {
        // trim() e strlen() existem no JS também — mesma ideia
        $nome = trim($nome);

        // Validação com comparação ESTRITA (===) — igual ao TS
        if ($nome === '') {
            throw new \InvalidArgumentException('O nome é obrigatório.');
        }

        if ($email !== null && trim($email) === '') {
            throw new \InvalidArgumentException('E-mail não pode ser só espaço.');
        }

        // Gera id simples: count do repositório + 1
        $novoId = count($this->repositorio->buscarTodos()) + 1;

        $usuario = new Usuario(
            id: $novoId,          // argumentos nomeados, tipo objeto literal do JS!
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
