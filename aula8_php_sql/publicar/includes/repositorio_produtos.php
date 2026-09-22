<?php
declare(strict_types=1);

/**
 * @return array<int, array<string, int|string>>
 */
function buscarProdutos(mysqli $mysqli, string $busca = ''): array
{
    $sql = <<<'SQL'
        SELECT
            p.id,
            p.nome,
            p.descricao,
            p.preco,
            p.estoque,
            c.nome AS categoria
        FROM produtos p
        INNER JOIN categorias c ON c.id = p.categoria_id
        WHERE (? = '' OR p.nome LIKE CONCAT('%', ?, '%') OR c.nome LIKE CONCAT('%', ?, '%'))
        ORDER BY p.nome
        SQL;

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sss', $busca, $busca, $busca);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
