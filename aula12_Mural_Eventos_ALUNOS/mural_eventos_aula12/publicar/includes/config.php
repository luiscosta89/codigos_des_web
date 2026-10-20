<?php
declare(strict_types=1);

/*
 * CONFIGURAÇÃO DO BANCO
 *
 * Cada aluno deve substituir SEU_USUARIO, SUA_SENHA e SEU_BANCO pelos dados
 * fornecidos para acesso ao MariaDB/phpMyAdmin da disciplina.
 * Nunca publique credenciais reais em repositórios públicos.
 */

$caminhoScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDetectada = rtrim(dirname($caminhoScript), '/');

/* Arquivos de cadastro ficam em /eventos, mas a raiz da aplicação é a pasta anterior. */
if (str_ends_with($baseDetectada, '/eventos')) {
    $baseDetectada = substr($baseDetectada, 0, -strlen('/eventos'));
}
if ($baseDetectada === '.' || $baseDetectada === '/') {
    $baseDetectada = '';
}

$baseAmbiente = getenv('APP_BASE');

return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'usuario' => getenv('DB_USER') ?: 'SEU_USUARIO',
    'senha' => getenv('DB_PASS') ?: 'SUA_SENHA',
    'banco' => getenv('DB_NAME') ?: 'SEU_BANCO',
    'porta' => (int) (getenv('DB_PORT') ?: 3306),
    'base_url' => $baseAmbiente !== false ? rtrim($baseAmbiente, '/') : $baseDetectada,
];
