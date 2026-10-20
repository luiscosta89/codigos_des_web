<?php
declare(strict_types=1);
$tituloPagina = $tituloPagina ?? 'Mural de Eventos Acadêmicos';
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= escapar($tituloPagina) ?> · Mural de Eventos</title>
  <link rel="stylesheet" href="<?= escapar(url('/assets/style.css')) ?>">
  <script src="<?= escapar(url('/assets/app.js')) ?>" defer></script>
</head>
<body>
<header class="site-header">
  <a class="brand" href="<?= escapar(url('/painel.php')) ?>">
    <span class="brand-mark">M</span>
    <span><strong>Mural de Eventos</strong><small>Acadêmicos</small></span>
  </a>
  <nav>
    <span>Olá, <?= escapar($_SESSION['usuario_nome'] ?? 'visitante') ?></span>
    <a class="btn ghost" href="<?= escapar(url('/logout.php')) ?>">Sair</a>
  </nav>
</header>
<main class="container">
