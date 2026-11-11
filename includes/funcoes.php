<?php
declare(strict_types=1);

/** converte qualquer valor em uma string segura para ser exibida em HTML (prevenção de XSS) */
function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Redireciona e encerra (usado no padrão PRG)
 * @param string $url
 * @return never Indica que a função nunca retorna: ela encerra a execução ou lança uma exceção.
 */
function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

/** Guarda mensagem na sessão para exibir uma única vez */
function flash(string $tipo, string $msg): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'msg' => $msg];
}

/** Consome as mensagens flash */
function pegar_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/** Gera (ou reaproveita) token CSRF da sessão */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Campo hidden pronto para usar em formulários */
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Aborta a requisição se o token for inválido */
function csrf_check(): void
{
    $enviado = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', (string) $enviado)) {
        http_response_code(419);
        exit('Token CSRF inválido ou expirado.');
    }
}

