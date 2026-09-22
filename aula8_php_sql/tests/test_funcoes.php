<?php
declare(strict_types=1);

require __DIR__ . '/../publicar/includes/funcoes.php';

function igual(mixed $esperado, mixed $atual, string $mensagem): void
{
    if ($esperado !== $atual) {
        fwrite(STDERR, "FALHOU: {$mensagem}\nEsperado: " . var_export($esperado, true) . "\nAtual: " . var_export($atual, true) . "\n");
        exit(1);
    }
}

igual('', normalizarBusca(null), 'busca ausente vira texto vazio');
igual('mouse sem fio', normalizarBusca('  mouse sem fio  '), 'busca remove espaços externos');
igual(80, mb_strlen(normalizarBusca(str_repeat('a', 100))), 'busca é limitada a 80 caracteres');
igual('R$ 199,90', formatarPreco(199.9), 'preço usa formato brasileiro');
igual('Esgotado', statusEstoque(0), 'estoque zero');
igual('Últimas unidades', statusEstoque(4), 'estoque baixo');
igual('Disponível', statusEstoque(12), 'estoque disponível');
igual('&lt;script&gt;', escapar('<script>'), 'HTML é escapado');

echo "OK: funções da TechStore\n";
