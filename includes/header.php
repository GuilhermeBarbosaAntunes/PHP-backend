<?php
if (!isset($titulo)) {
    $titulo = 'MeuSite';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> | MeuSite</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header class="topo">
        <div class="container topo-inner">
            <a href="index.php" class="logo">🐘 MeuSite</a>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Tarefas</a></li>
                    <li><a href="contato.php">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php foreach (pegar_flashes() as $f): ?>
            <div class="alerta <?= e($f['tipo']) ?>"><?= e($f['msg']) ?></div>
        <?php endforeach; ?>