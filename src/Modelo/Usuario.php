<?php

namespace App\Modelo;

/**
 * DTO (objeto de dados) — o substituto do "shape" do TypeScript.
 *
 * Em TS você faria:  type Usuario = { id: number; nome: string; ... }
 * Em PHP, para ter tipos de verdade, usamos uma CLASSE.
 *
 * Conceitos novos para você (PHP 8+):
 * - "constructor promotion": declara e tipa as propriedades direto no construtor
 *   (em vez de declarar campo por campo como em classes TS)
 * - "readonly": igual ao readonly do TS — não pode alterar depois de criado
 */
final class Usuario
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome,
        // "?string" = "string | null" do TS. Pode ser omitido.
        public readonly ?string $email = null,
    ) {
    }

    /**
     * Método com tipagem de parâmetro e de retorno, igual ao TS:
     *   apresentar(): string
     */
    public function apresentar(): string
    {
        // Interpolação: aspas duplas interpolam variáveis (template string do JS)
        // "??" é o null coalescing — idêntico ao JS/TS
        $emailInfo = $this->email ?? 'sem e-mail';

        return "{$this->nome} (id {$this->id}) — {$emailInfo}";
    }

    /**
     * Converte para array associativo (como o .toJSON() / spread de objeto do JS)
     */
    public function paraArray(): array
    {
        return [
            'id'    => $this->id,
            'nome'  => $this->nome,
            'email' => $this->email,
        ];
    }
}
