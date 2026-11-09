<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $nome     = trim((string) ($_POST['nome'] ?? ''));
    $email    = trim((string) ($_POST['email'] ?? ''));
    $mensagem = trim((string) ($_POST['mensagem'] ?? ''));

    $erros = [];

    if (mb_strlen($nome) < 3) {
        $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }
    if (mb_strlen($mensagem) < 10) {
        $erros[] = 'A mensagem deve ter pelo menos 10 caracteres.';
    }

    if ($erros) {
        foreach ($erros as $msg) {
            flash('erro', $msg);
        }
    } else {
        // Uso de transação (bom hábito mesmo com 1 INSERT)
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO mensagens (nome, email, mensagem, ip)
                 VALUES (:n, :e, :m, :ip)'
            );
            $stmt->execute([
                ':n'  => $nome,
                ':e'  => $email,
                ':m'  => $mensagem,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
            $pdo->commit();
            flash('sucesso', 'Mensagem gravada no banco com sucesso.');
        } catch (Throwable $ex) {
            $pdo->rollBack();
            flash('erro', 'Falha ao salvar. Tente novamente.');
        }
    }

    redirect('contato.php');
}

// Últimas mensagens (para conferir que o INSERT funcionou)
$mensagens = $pdo->query(
    'SELECT nome, email, mensagem, criado_em
     FROM mensagens
     ORDER BY id DESC
     LIMIT 5'
)->fetchAll();

$total = (int) $pdo->query('SELECT COUNT(*) FROM mensagens')->fetchColumn();

$titulo = 'Contato';
include __DIR__ . '/includes/header.php';
?>

<h1>✉️ Fale Conosco</h1>
<p class="subtitulo">Total de mensagens no banco: <strong><?= $total ?></strong></p>

<form method="POST" class="form-contato">
    <?= csrf_campo() ?>

    <label>Nome</label>
    <input type="text" name="nome" maxlength="80" required>

    <label>E-mail</label>
    <input type="email" name="email" maxlength="120" required>

    <label>Mensagem</label>
    <textarea name="mensagem" rows="5" minlength="10" required></textarea>

    <button class="btn" type="submit">Enviar</button>
</form>

<h2>Últimas mensagens</h2>
<?php if (!$mensagens): ?>
    <p class="vazio">Nada gravado ainda.</p>
<?php else: ?>
    <ul class="lista-mensagens">
        <?php foreach ($mensagens as $m): ?>
            <li>
                <strong><?= e($m['nome']) ?></strong>
                <span class="email">&lt;<?= e($m['email']) ?>&gt;</span>
                <small class="data"><?= e($m['criado_em']) ?></small>
                <p><?= nl2br(e($m['mensagem'])) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>