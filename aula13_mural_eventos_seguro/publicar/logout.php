<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/autenticacao.php';
exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Método não permitido.'); }
exigir_csrf($_POST['csrf_token'] ?? null);
encerrar_sessao();
header('Location: ' . url('/login.php'));
exit;
