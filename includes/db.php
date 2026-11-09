<?php
declare(strict_types=1);

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'estudo_php';
$dbUser = getenv('DB_USER');
$dbPassword = getenv('DB_PASSWORD');

if ($dbPassword === false) {
    $dbPassword = '';
}

if ($dbUser === false || $dbUser === '') {
    http_response_code(500);
    exit('Configure DB_USER e as demais variáveis de conexão com o MySQL antes de iniciar o projeto.');
}

if (
    preg_match('/\A[a-zA-Z0-9.-]+\z/', $dbHost) !== 1
    || filter_var($dbPort, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 65535],
    ]) === false
    || preg_match('/\A[a-zA-Z0-9_]+\z/', $dbName) !== 1
) {
    http_response_code(500);
    exit('Configuração inválida de host, porta ou nome do banco MySQL.');
}

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $dbHost,
    $dbPort,
    $dbName
);

try {
    $pdo = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    error_log('Falha ao conectar ao MySQL: ' . $e->getMessage());
    http_response_code(500);
    exit('Falha ao conectar ao MySQL. Confira se o servidor está ativo e se as configurações DB_* estão corretas.');
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS tarefas (
        id        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        titulo    VARCHAR(120) NOT NULL,
        concluida TINYINT(1) NOT NULL DEFAULT 0,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS mensagens (
        id        INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome      VARCHAR(80) NOT NULL,
        email     VARCHAR(120) NOT NULL,
        mensagem  TEXT NOT NULL,
        ip        VARCHAR(45) NULL,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
