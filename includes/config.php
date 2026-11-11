<?php
declare(strict_types=1);

date_default_timezone_set('America/Sao_Paulo');

// Secure session (HttpOnly + SameSite cookie)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

// In production: display_errors = 0 and log_errors = 1
error_reporting(E_ALL);
ini_set('display_errors', '1');