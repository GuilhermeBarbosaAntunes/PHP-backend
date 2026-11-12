<?php
if (!isset($Title)) {
    $Title = 'My Site';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($Title) ?> | My Site</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header class="topo">
        <div class="container topo-inner">
            <a href="index.php" class="logo">🐘 My Site</a>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Task</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php foreach (get_flashes() as $f): ?>
            <div class="alert <?= e($f['tipo']) ?>"><?= e($f['msg']) ?></div>
        <?php endforeach; ?>