<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/function.php';
$pdo = require __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $name     = trim((string) ($_POST['name'] ?? ''));
    $email    = trim((string) ($_POST['email'] ?? ''));
    $messages = trim((string) ($_POST['message'] ?? ''));

    $errors = [];

    if (mb_strlen($name) < 3) {
        $errors[] = 'The name must have at least 3 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (mb_strlen($messages) < 10) {
        $errors[] = 'The message must have at least 10 characters.';
    }

    if ($errors) {
        foreach ($errors as $msg) {
            flash('error', $msg);
        }
    } else {
        // Use a transaction (a good practice even with a single INSERT)
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO messages (name, email, message, ip)
                 VALUES (:n, :e, :m, :ip)'
            );
            $stmt->execute([
                ':n'  => $name,
                ':e'  => $email,
                ':m'  => $messages,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
            $pdo->commit();
            flash('success', 'Message saved to the database successfully.');
        } catch (Throwable $ex) {
            $pdo->rollBack();
            flash('error', 'Failed to save. Please try again.');
        }
    }

    redirect('contact.php');
}

// Latest messages (to verify that the INSERT worked)
$messages = $pdo->query(
    'SELECT name, email, message, created_at
     FROM messages
     ORDER BY id DESC
     LIMIT 5'
)->fetchAll();

$total = (int) $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();

$titulo = 'Contact';
include __DIR__ . '/includes/header.php';
?>

<h1>✉️ Contact Us</h1>
<p class="subtitle">Total number of messages in the database: <strong><?= $total ?></strong></p>

<form method="POST" class="form-contact">
    <?= csrf_campo() ?>

    <label>Name</label>
    <input type="text" name="name" maxlength="80" required>

    <label>E-mail</label>
    <input type="email" name="email" maxlength="120" required>

    <label>Message</label>
    <textarea name="message" rows="5" minlength="10" required></textarea>

    <button class="btn" type="submit">Send</button>
</form>

<h2>Latest Messages</h2>
<?php if (!$messages): ?>
    <p class="void">No messages saved yet.</p>
<?php else: ?>
    <ul class="message-list">
        <?php foreach ($messages as $m): ?>
            <li>
                <strong><?= e($m['name']) ?></strong>
                <span class="email">&lt;<?= e($m['email']) ?>&gt;</span>
                <small class="data"><?= e($m['created_at']) ?></small>
                <p><?= nl2br(e($m['message'])) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>