<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/http.php';
require_once __DIR__ . '/includes/roteamento.php';
require_once __DIR__ . '/includes/validacao.php';
require_once __DIR__ . '/includes/banco.php';
require_once __DIR__ . '/includes/repositorio.php';

$config = require __DIR__ . '/config.php';

header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');
header('Vary: Origin');

$origem = $_SERVER['HTTP_ORIGIN'] ?? null;
$origemAceita = origemPermitida($origem, $config['cors_origens']);
if ($origemAceita !== null) {
    header('Access-Control-Allow-Origin: ' . $origemAceita);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    enviarVazio(204);
}

try {
    $metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    [$tipoRota, $id] = interpretarRota((string) ($_GET['rota'] ?? '/pontos'));

    if ($tipoRota === null) {
        enviarErro(404, 'rota_nao_encontrada', 'Rota não encontrada.');
    }

    $permitidos = $tipoRota === 'colecao' ? ['GET', 'POST'] : ['GET', 'PUT', 'DELETE'];
    if (!in_array($metodo, $permitidos, true)) {
        header('Allow: ' . implode(', ', $permitidos));
        enviarErro(
            405,
            'metodo_nao_permitido',
            'Método não permitido nesta rota.',
            [],
        );
    }

    if ($metodo !== 'GET') {
        validarChaveApi($config['api_key']);
    }

    $repo = new RepositorioPontos(conectarBanco($config['db']));

    if ($tipoRota === 'colecao' && $metodo === 'GET') {
        listarPontos($repo);
    }
    if ($tipoRota === 'colecao' && $metodo === 'POST') {
        criarPonto($repo, $config);
    }
    if ($tipoRota === 'item' && $metodo === 'GET') {
        buscarPonto($repo, $id);
    }
    if ($tipoRota === 'item' && $metodo === 'PUT') {
        atualizarPonto($repo, $id);
    }
    if ($tipoRota === 'item' && $metodo === 'DELETE') {
        excluirPonto($repo, $id);
    }
} catch (DominioDuplicadoException) {
    enviarErro(409, 'conflito', 'Já existe um ponto com este nome no bairro informado.');
} catch (InvalidArgumentException $erro) {
    enviarErro(400, 'requisicao_invalida', $erro->getMessage());
} catch (Throwable $erro) {
    error_log('Falha interna na API de pontos: ' . $erro->getMessage());
    enviarErro(500, 'erro_interno', 'Não foi possível concluir a operação.');
}

function validarChaveApi(string $chaveConfigurada): void
{
    if ($chaveConfigurada === 'TROQUE_POR_UMA_CHAVE_LONGA_E_ALEATORIA') {
        throw new RuntimeException('API key ainda não configurada.');
    }

    $recebida = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if ($recebida === '' || !hash_equals($chaveConfigurada, $recebida)) {
        header('WWW-Authenticate: ApiKey realm="aula14", header="X-API-Key"');
        enviarErro(401, 'nao_autorizado', 'Informe uma X-API-Key válida.');
    }
}

function lerCorpoJson(): array
{
    $contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
    if ($contentType !== 'application/json') {
        enviarErro(415, 'tipo_nao_suportado', 'Envie Content-Type: application/json.');
    }

    $corpo = file_get_contents('php://input');
    try {
        $dados = json_decode($corpo, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        enviarErro(400, 'json_invalido', 'O corpo não contém JSON válido.');
    }

    if (!is_array($dados) || array_is_list($dados)) {
        enviarErro(400, 'json_invalido', 'Envie um objeto JSON.');
    }
    return $dados;
}

function validarEntidade(array $dados): array
{
    $resultado = validarPonto($dados);
    if ($resultado['erros'] !== []) {
        enviarErro(422, 'validacao', 'Corrija os campos indicados.', $resultado['erros']);
    }
    return $resultado['valores'];
}

function listarPontos(RepositorioPontos $repo): never
{
    try {
        $paginacao = lerPaginacao($_GET);
    } catch (InvalidArgumentException) {
        enviarErro(400, 'paginacao_invalida', 'pagina deve ser positiva e limite deve estar entre 1 e 100.');
    }

    $filtros = validarFiltros($_GET);
    if ($filtros['erros'] !== []) {
        enviarErro(422, 'filtros_invalidos', 'Corrija os filtros indicados.', $filtros['erros']);
    }

    $dados = $repo->listar($filtros['valores'], $paginacao['limite'], $paginacao['deslocamento']);
    enviarJson(200, [
        'dados' => $dados,
        'paginacao' => [
            'pagina' => $paginacao['pagina'],
            'limite' => $paginacao['limite'],
            'total' => $repo->contar($filtros['valores']),
        ],
    ]);
}

function buscarPonto(RepositorioPontos $repo, int $id): never
{
    $ponto = $repo->buscar($id);
    if ($ponto === null) {
        enviarErro(404, 'nao_encontrado', 'Ponto de coleta não encontrado.');
    }
    enviarJson(200, ['dados' => $ponto]);
}

function criarPonto(RepositorioPontos $repo, array $config): never
{
    $id = $repo->criar(validarEntidade(lerCorpoJson()));
    $ponto = $repo->buscar($id);
    $base = $config['base_url'] ?? detectarBase($_SERVER['SCRIPT_NAME'] ?? '/api/index.php');
    $location = rtrim($base, '/') . '/api/index.php?rota=/pontos/' . $id;
    enviarJson(201, ['dados' => $ponto], ['Location' => $location]);
}

function atualizarPonto(RepositorioPontos $repo, int $id): never
{
    if ($repo->buscar($id) === null) {
        enviarErro(404, 'nao_encontrado', 'Ponto de coleta não encontrado.');
    }
    $repo->atualizar($id, validarEntidade(lerCorpoJson()));
    enviarJson(200, ['dados' => $repo->buscar($id)]);
}

function excluirPonto(RepositorioPontos $repo, int $id): never
{
    if (!$repo->excluir($id)) {
        enviarErro(404, 'nao_encontrado', 'Ponto de coleta não encontrado.');
    }
    enviarVazio(204);
}
