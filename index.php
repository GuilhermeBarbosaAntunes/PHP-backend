<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
$pdo = require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/function.php';

$filter = $_GET['filter'] ?? 'all';
$search  = trim((string) ($_GET['search'] ?? ''));

/* ============================================================
   ACTIONS (POST) - all use PRG: process, then redirect
   ============================================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'create':
            $title = trim((string) ($_POST['title'] ?? ''));

            if ($title === '') {
                flash('error', 'The title cannot be left blank.');
            } elseif (mb_strlen($title) > 120) {
                flash('error', 'Maximum of 120 characters in the title.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO tasks (title) VALUES (:t)');
                $stmt->execute([':t' => $title]);
                flash('success', 'Task created.');
            }
            break;

        case 'toggle':
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare(
                'UPDATE tasks SET completed = 1 - completed WHERE id = :id'
            );
            $stmt->execute([':id' => $id]);
            flash('success', 'Status changed.');
            break;

        case 'delete':
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
            $stmt->execute([':id' => $id]);
            flash('success', 'Task removed.');
            break;
    }

    redirect('index.php?filter=' . urlencode($filter) . '&search=' . urlencode($search));
}

/* ============================================================
   QUERIES (GET)
   ============================================================ */
$where  = [];
$params = [];

if ($filter === 'pending')  { $where[] = 'completed = 0'; }
if ($filter === 'completed') { $where[] = 'completed = 1'; }

if ($search !== '') {
    $where[] = 'title LIKE :search';
    $params[':search'] = '%' . $search . '%';
}

$sql = 'SELECT id, title, completed, created_at FROM tasks';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY completed ASC, id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

$stats = $pdo->query("
    SELECT COUNT(*) AS total,
           COALESCE(SUM(CASE WHEN completed = 0 THEN 1 ELSE 0 END), 0) AS pending,
           COALESCE(SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END), 0) AS completed
    FROM tasks
")->fetch();

$title = 'Tasks';
include __DIR__ . '/includes/header.php';
?>

<h1>📋 Gerenciador de Tarefas</h1>

<section class="stats">
    <div class="stat"><span><?= (int) $stats['total'] ?></span>Total</div>
    <div class="stat pending"><span><?= (int) $stats['pending'] ?></span>Pending</div>
    <div class="stat completed"><span><?= (int) $stats['completed'] ?></span>Completed</div>
</section>

<form method="POST" class="form-inline">
    <?= csrf_campo() ?>
    <input type="hidden" name="action" value="create">
    <input type="text" name="title" placeholder="New task…" maxlength="120" required autofocus>
    <button class="btn" type="submit">Add</button>
</form>

<form method="GET" class="filters">
    <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search…">
    <button class="btn btn-sec" type="submit">Filter</button>

    <nav class="tabs">
        <a href="?filter=all<?= $search ? '&search=' . urlencode($search) : '' ?>"
           class="<?= $filter === 'all' ? 'active' : '' ?>">All</a>
        <a href="?filter=pending<?= $search ? '&search=' . urlencode($search) : '' ?>"
           class="<?= $filter === 'pending' ? 'active' : '' ?>">Pending</a>
        <a href="?filter=completed<?= $search ? '&search=' . urlencode($search) : '' ?>"
           class="<?= $filter === 'completed' ? 'active' : '' ?>">Completed</a>
    </nav>
</form>

<?php if (!$tasks): ?>
    <p class="void">No tasks found.</p>
<?php else: ?>
    <ul class="todo-list">
        <?php foreach ($tasks as $t): ?>
            <li class="<?= $t['completed'] ? 'made' : '' ?>">
                <span class="title"><?= e($t['title']) ?></span>
                <small class="data"><?= e($t['created_at']) ?></small>

                <form method="POST" class="inline">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="action" value="toggle">
                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button class="btn-mini" type="submit">
                        <?= $t['completed'] ? '↩ Reopen' : '✔ Complete' ?>
                    </button>
                </form>

                <form method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button class="btn-mini btn-perigo" type="submit">🗑 Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>