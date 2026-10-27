<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('session.use_strict_mode', '1');

require_once __DIR__ . '/funcoes.php';
set_exception_handler(function (Throwable $erro): void {
    $referencia = bin2hex(random_bytes(8));
    error_log("Erro [$referencia] " . get_class($erro) . ' em ' . basename($erro->getFile()) . ':' . $erro->getLine());
    if (!headers_sent()) http_response_code(500);
    echo 'Não foi possível concluir a operação. Referência: ' . escapar($referencia);
});

$config = require __DIR__ . '/config.php';
enviar_cabecalhos_seguranca();

$https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && strtolower((string) $_SERVER['HTTPS']) !== 'off';
$caminhoCookie = rtrim((string) $config['base_url'], '/') . '/';
session_set_cookie_params([
    'lifetime' => 0, 'path' => $caminhoCookie, 'domain' => '',
    'secure' => $https, 'httponly' => true, 'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

if (isset($_SESSION['usuario_id'], $_SESSION['ultima_atividade']) && sessao_expirada((int) $_SESSION['ultima_atividade'], time())) {
    $_SESSION = [];
    session_regenerate_id(true);
    $_SESSION['flash_login'] = 'Sua sessão expirou por inatividade. Entre novamente.';
} elseif (isset($_SESSION['usuario_id'])) {
    $_SESSION['ultima_atividade'] = time();
}

validar_configuracao($config);
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/repositorio_eventos.php';
require_once __DIR__ . '/repositorio_usuarios.php';
$mysqli = conectar($config);
