<?php
declare(strict_types=1);

require __DIR__ . '/../publicar/includes/repositorio_produtos.php';

$mysqli = new mysqli(
    getenv('DB_HOST') ?: 'banco',
    getenv('DB_USER') ?: 'techstore',
    getenv('DB_PASSWORD') ?: 'techstore',
    getenv('DB_NAME') ?: 'techstore',
    (int) (getenv('DB_PORT') ?: 3306)
);
$mysqli->set_charset('utf8mb4');

function afirmar(bool $condicao, string $mensagem): void
{
    if (!$condicao) {
        fwrite(STDERR, "FALHOU: {$mensagem}\n");
        exit(1);
    }
}

$todos = buscarProdutos($mysqli, '');
afirmar(count($todos) === 3, 'lista todos os produtos');
afirmar($todos[0]['nome'] === 'Headset USB', 'ordena por nome');
afirmar($todos[0]['categoria'] === 'Áudio', 'inclui nome da categoria');

$mouse = buscarProdutos($mysqli, 'mouse');
afirmar(count($mouse) === 1, 'filtra por parte do nome');
afirmar($mouse[0]['nome'] === 'Mouse Sem Fio', 'retorna o produto filtrado');

$perifericos = buscarProdutos($mysqli, 'periféricos');
afirmar(count($perifericos) === 2, 'filtra pelo nome da categoria');

echo "OK: consultas da TechStore\n";
