<?php
declare(strict_types=1);

/** Converts any value to a safe string for HTML output (XSS prevention). */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Redirects and exits (used in the PRG pattern).
 * @param string $url
 * @return never Indicates that the function never returns: it exits execution or throws an exception.
 */
function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

/** Stores a message in the session to display it once. */
function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

/** Retrieves and clears flash messages. */
function pegar_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/** Generates or reuses the session's CSRF token. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/** Ready-to-use hidden field for forms. */
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Aborts the request if the token is invalid. */
function csrf_check(): void
{
    $sended = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', (string) $sended)) {
        http_response_code(419);
        exit('Invalid or expired CSRF token.');
    }
}

