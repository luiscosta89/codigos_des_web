<?php
declare(strict_types=1);

$config = require __DIR__ . '/config.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/funcoes.php';
require_once __DIR__ . '/repositorio_eventos.php';
require_once __DIR__ . '/repositorio_usuarios.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$mysqli = conectar($config);
