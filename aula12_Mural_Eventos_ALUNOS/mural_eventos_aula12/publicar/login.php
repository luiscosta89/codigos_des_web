<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . url('/painel.php'));
    exit;
}

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim((string) ($_POST['login'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');
    $usuario = autenticar_usuario($mysqli, $login, $senha);
    if ($usuario) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int) $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: ' . url('/painel.php'));
        exit;
    }
    $erro = 'Login ou senha inválidos.';
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Entrar · Mural de Eventos Acadêmicos</title>
  <link rel="stylesheet" href="<?= escapar(url('/assets/style.css')) ?>">
</head>
<body class="login-page">
<main class="login-card">
  <span class="eyebrow">AULA 12 · PHP + MYSQLI</span>
  <h1>Mural de Eventos Acadêmicos</h1>
  <p class="muted">Entre para cadastrar, editar e publicar eventos.</p>
  <?php if ($erro): ?><div class="alert error"><?= escapar($erro) ?></div><?php endif; ?>
  <form method="post" class="form-grid">
    <label>Login<input name="login" autocomplete="username" required></label>
    <label>Senha<input name="senha" type="password" autocomplete="current-password" required></label>
    <button class="btn primary">Entrar no sistema</button>
  </form>
  <p class="demo">Demonstração: <strong>admin</strong> / <strong>aula12</strong></p>
</main>
</body>
</html>
