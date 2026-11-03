<?php

declare(strict_types=1);

function detectarBase(string $scriptName): string
{
    $sufixo = '/api/index.php';
    if (!str_ends_with($scriptName, $sufixo)) {
        return rtrim(dirname($scriptName), '/.');
    }

    return substr($scriptName, 0, -strlen($sufixo));
}

function origemPermitida(?string $origem, array $allowlist): ?string
{
    if ($origem === null || $origem === '') {
        return null;
    }

    return in_array($origem, $allowlist, true) ? $origem : null;
}

function enviarJson(int $status, array $dados, array $headers = []): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    foreach ($headers as $nome => $valor) {
        header($nome . ': ' . $valor);
    }
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function enviarVazio(int $status, array $headers = []): never
{
    http_response_code($status);
    foreach ($headers as $nome => $valor) {
        header($nome . ': ' . $valor);
    }
    exit;
}

function enviarErro(int $status, string $codigo, string $mensagem, array $detalhes = []): never
{
    $erro = ['codigo' => $codigo, 'mensagem' => $mensagem];
    if ($detalhes !== []) {
        $erro['detalhes'] = $detalhes;
    }
    enviarJson($status, ['erro' => $erro]);
}
