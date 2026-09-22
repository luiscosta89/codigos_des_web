<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$config = require __DIR__ . '/../config.php';

$host = getenv('DB_HOST') ?: $config['host'];
$banco = getenv('DB_NAME') ?: $config['banco'];
$usuario = getenv('DB_USER') ?: $config['usuario'];
$senha = getenv('DB_PASSWORD') ?: $config['senha'];
$porta = (int) (getenv('DB_PORT') ?: ($config['porta'] ?? 3306));

if (str_starts_with($banco, 'SEU_') || str_starts_with($usuario, 'SEU_')) {
    throw new RuntimeException('Edite config.php com os dados do seu banco antes de abrir a aplicação.');
}

$mysqli = new mysqli($host, $usuario, $senha, $banco, $porta);
$mysqli->set_charset('utf8mb4');
