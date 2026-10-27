<?php
declare(strict_types=1);

/* Edite somente os três placeholders abaixo na hospedagem da disciplina. */
$baseAmbiente = getenv('APP_BASE');
$baseDetectada = detectar_base_url((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$baseUrl = $baseAmbiente !== false ? rtrim($baseAmbiente, '/') : $baseDetectada;
if ($baseUrl !== '' && !preg_match('#^(?:/[A-Za-z0-9._~-]+)+$#D', $baseUrl)) {
    throw new RuntimeException('APP_BASE inválida. Use apenas um caminho como /mural-eventos.');
}

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'usuario' => getenv('DB_USER') ?: 'SEU_USUARIO',
    'senha' => getenv('DB_PASS') ?: 'SUA_SENHA',
    'banco' => getenv('DB_NAME') ?: 'SEU_BANCO',
    'porta' => (int) (getenv('DB_PORT') ?: 3306),
    'base_url' => $baseUrl,
];
