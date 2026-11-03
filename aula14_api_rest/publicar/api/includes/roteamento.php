<?php

declare(strict_types=1);

function interpretarRota(string $rota): array
{
    $caminho = '/' . trim($rota, '/');
    if ($caminho === '/pontos') {
        return ['colecao', null];
    }

    if (preg_match('#^/pontos/([1-9][0-9]*)$#', $caminho, $partes) === 1) {
        return ['item', (int) $partes[1]];
    }

    return [null, null];
}
