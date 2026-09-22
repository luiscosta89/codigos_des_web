<?php
declare(strict_types=1);

function normalizarBusca(?string $busca): string
{
    return mb_substr(trim($busca ?? ''), 0, 80);
}

function formatarPreco(float|string $preco): string
{
    return 'R$ ' . number_format((float) $preco, 2, ',', '.');
}

function statusEstoque(int $quantidade): string
{
    if ($quantidade === 0) {
        return 'Esgotado';
    }

    return $quantidade < 5 ? 'Últimas unidades' : 'Disponível';
}

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
