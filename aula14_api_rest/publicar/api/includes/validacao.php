<?php

declare(strict_types=1);

const TIPOS_PONTO = ['eletronicos', 'oleo', 'pilhas', 'reciclaveis', 'medicamentos'];

function lerPaginacao(array $query): array
{
    $limite = filter_var($query['limite'] ?? 20, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 100],
    ]);
    $pagina = filter_var($query['pagina'] ?? 1, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($limite === false || $pagina === false) {
        throw new InvalidArgumentException('Paginação inválida.');
    }

    $indicePagina = $pagina - 1;
    if ($indicePagina > intdiv(PHP_INT_MAX, $limite)) {
        throw new InvalidArgumentException('Paginação inválida.');
    }

    return ['limite' => $limite, 'pagina' => $pagina, 'deslocamento' => $indicePagina * $limite];
}

function validarFiltros(array $query): array
{
    $valores = [];
    $erros = [];

    if (isset($query['tipo']) && $query['tipo'] !== '') {
        if (!in_array($query['tipo'], TIPOS_PONTO, true)) {
            $erros['tipo'] = 'Use um tipo permitido.';
        } else {
            $valores['tipo'] = $query['tipo'];
        }
    }

    if (isset($query['bairro']) && $query['bairro'] !== '') {
        $bairro = trim((string) $query['bairro']);
        if (mb_strlen($bairro) > 100) {
            $erros['bairro'] = 'Use no máximo 100 caracteres.';
        } else {
            $valores['bairro'] = $bairro;
        }
    }

    if (isset($query['ativo']) && $query['ativo'] !== '') {
        $ativo = filter_var($query['ativo'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($ativo === null) {
            $erros['ativo'] = 'Use true, false, 1 ou 0.';
        } else {
            $valores['ativo'] = $ativo;
        }
    }

    return ['valores' => $valores, 'erros' => $erros];
}

function validarPonto(array $dados): array
{
    $valores = [];
    $erros = [];

    foreach (['nome' => 150, 'bairro' => 100, 'endereco' => 150] as $campo => $maximo) {
        $valor = trim((string) ($dados[$campo] ?? ''));
        if ($valor === '') {
            $erros[$campo] = 'Campo obrigatório.';
        } elseif (mb_strlen($valor) > $maximo) {
            $erros[$campo] = "Use no máximo {$maximo} caracteres.";
        } else {
            $valores[$campo] = $valor;
        }
    }

    $tipo = (string) ($dados['tipo'] ?? '');
    if (!in_array($tipo, TIPOS_PONTO, true)) {
        $erros['tipo'] = 'Use um tipo permitido.';
    } else {
        $valores['tipo'] = $tipo;
    }

    foreach (['latitude' => [-90, 90], 'longitude' => [-180, 180]] as $campo => [$minimo, $maximo]) {
        if (!isset($dados[$campo]) || !is_numeric($dados[$campo])) {
            $erros[$campo] = 'Informe um número.';
            continue;
        }
        $numero = (float) $dados[$campo];
        if ($numero < $minimo || $numero > $maximo) {
            $erros[$campo] = "Use um valor entre {$minimo} e {$maximo}.";
        } else {
            $valores[$campo] = $numero;
        }
    }

    if (!array_key_exists('ativo', $dados) || !is_bool($dados['ativo'])) {
        $erros['ativo'] = 'Informe true ou false.';
    } else {
        $valores['ativo'] = $dados['ativo'];
    }

    return ['valores' => $valores, 'erros' => $erros];
}
