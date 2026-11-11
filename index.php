<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/funcoes.php';

$filtro = $_GET['filtro'] ?? 'todas';
$busca  = trim((string) ($_GET['busca'] ?? ''));

/* ============================================================
   ACTIONS (POST) - all use PRG: process, then redirect
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $acao = $_POST['acao'] ?? '';

    switch ($acao) {

        case 'criar':
            $titulo = trim((string) ($_POST['titulo'] ?? ''));

            if ($titulo === '') {
                flash('erro', 'O título não pode ficar vazio.');
            } elseif (mb_strlen($titulo) > 120) {
                flash('erro', 'Máximo de 120 caracteres no título.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO tarefas (titulo) VALUES (:t)');
                $stmt->execute([':t' => $titulo]);
                flash('sucesso', 'Tarefa criada.');
            }
            break;

        case 'alternar':
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare(
                'UPDATE tarefas SET concluida = 1 - concluida WHERE id = :id'
            );
            $stmt->execute([':id' => $id]);
            flash('sucesso', 'Status alterado.');
            break;

        case 'excluir':
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('DELETE FROM tarefas WHERE id = :id');
            $stmt->execute([':id' => $id]);
            flash('sucesso', 'Tarefa removida.');
            break;
    }

    redirect('index.php?filtro=' . urlencode($filtro) . '&busca=' . urlencode($busca));
}

/* ============================================================
   QUERIES (GET)
   ============================================================ */
$where  = [];
$params = [];

if ($filtro === 'pendentes')  { $where[] = 'concluida = 0'; }
if ($filtro === 'concluidas') { $where[] = 'concluida = 1'; }

if ($busca !== '') {
    $where[] = 'titulo LIKE :busca';
    $params[':busca'] = '%' . $busca . '%';
}

$sql = 'SELECT id, titulo, concluida, criado_em FROM tarefas';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY concluida ASC, id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tarefas = $stmt->fetchAll();

$stats = $pdo->query("
    SELECT COUNT(*) AS total,
           COALESCE(SUM(CASE WHEN concluida = 0 THEN 1 ELSE 0 END), 0) AS pendentes,
           COALESCE(SUM(CASE WHEN concluida = 1 THEN 1 ELSE 0 END), 0) AS concluidas
    FROM tarefas
")->fetch();

$titulo = 'Tarefas';
include __DIR__ . '/includes/header.php';
?>

<h1>📋 Gerenciador de Tarefas</h1>

<section class="stats">
    <div class="stat"><span><?= (int) $stats['total'] ?></span>Total</div>
    <div class="stat pendente"><span><?= (int) $stats['pendentes'] ?></span>Pendentes</div>
    <div class="stat feita"><span><?= (int) $stats['concluidas'] ?></span>Concluídas</div>
</section>

<form method="POST" class="form-inline">
    <?= csrf_campo() ?>
    <input type="hidden" name="acao" value="criar">
    <input type="text" name="titulo" placeholder="Nova tarefa…" maxlength="120" required autofocus>
    <button class="btn" type="submit">Adicionar</button>
</form>

<form method="GET" class="filtros">
    <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Buscar…">
    <button class="btn btn-sec" type="submit">Filtrar</button>

    <nav class="tabs">
        <a href="?filtro=todas<?= $busca ? '&busca=' . urlencode($busca) : '' ?>"
           class="<?= $filtro === 'todas' ? 'ativo' : '' ?>">Todas</a>
        <a href="?filtro=pendentes<?= $busca ? '&busca=' . urlencode($busca) : '' ?>"
           class="<?= $filtro === 'pendentes' ? 'ativo' : '' ?>">Pendentes</a>
        <a href="?filtro=concluidas<?= $busca ? '&busca=' . urlencode($busca) : '' ?>"
           class="<?= $filtro === 'concluidas' ? 'ativo' : '' ?>">Concluídas</a>
    </nav>
</form>

<?php if (!$tarefas): ?>
    <p class="vazio">Nenhuma tarefa encontrada.</p>
<?php else: ?>
    <ul class="lista-tarefas">
        <?php foreach ($tarefas as $t): ?>
            <li class="<?= $t['concluida'] ? 'feita' : '' ?>">
                <span class="titulo"><?= e($t['titulo']) ?></span>
                <small class="data"><?= e($t['criado_em']) ?></small>

                <form method="POST" class="inline">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="acao" value="alternar">
                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button class="btn-mini" type="submit">
                        <?= $t['concluida'] ? '↩ Reabrir' : '✔ Concluir' ?>
                    </button>
                </form>

                <form method="POST" class="inline" onsubmit="return confirm('Excluir esta tarefa?')">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button class="btn-mini btn-perigo" type="submit">🗑 Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>