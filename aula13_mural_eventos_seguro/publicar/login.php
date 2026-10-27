<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

if (isset($_SESSION['usuario_id'])) { header('Location: ' . url('/painel.php')); exit; }
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) { http_response_code(405); header('Allow: GET, POST'); exit('Método não permitido.'); }

$erro = $_SESSION['flash_login'] ?? null;
unset($_SESSION['flash_login']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigir_csrf($_POST['csrf_token'] ?? null);
    $login = trim(is_string($_POST['login'] ?? null) ? $_POST['login'] : '');
    $senha = is_string($_POST['senha'] ?? null) ? $_POST['senha'] : '';
    $usuario = autenticar_usuario($mysqli, $login, $senha);
    if ($usuario) {
        session_regenerate_id(true);
        unset($_SESSION['csrf_token']);
        $_SESSION['usuario_id'] = (int) $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['ultima_atividade'] = time();
        header('Location: ' . url('/painel.php')); exit;
    }
    $erro = 'Login ou senha inválidos.';
}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Entrar · Mural de Eventos Acadêmicos</title><link rel="stylesheet" href="<?= escapar(url('/assets/style.css')) ?>"></head>
<body class="login-page"><main class="login-card">
<span class="eyebrow">AULA 13 · VERSÃO PROTEGIDA</span><h1>Mural de Eventos Acadêmicos</h1>
<p class="muted">Entre para gerenciar somente os seus eventos.</p>
<?php if ($erro): ?><div class="alert error"><?= escapar($erro) ?></div><?php endif; ?>
<form method="post" class="form-grid">
<input type="hidden" name="csrf_token" value="<?= escapar(csrf_token()) ?>">
<label>Login<input name="login" autocomplete="username" required></label>
<label>Senha<input name="senha" type="password" autocomplete="current-password" required></label>
<button class="btn primary" type="submit">Entrar no sistema</button></form>
</main></body></html>
