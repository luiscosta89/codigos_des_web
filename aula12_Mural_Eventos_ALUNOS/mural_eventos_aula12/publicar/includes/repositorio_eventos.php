<?php
declare(strict_types=1);

function listar_eventos(mysqli $mysqli): array
{
    $resultado = $mysqli->query(
        'SELECT id, titulo, descricao, data_evento, local_evento, situacao, cartaz
         FROM eventos ORDER BY data_evento, titulo'
    );
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

function buscar_evento(mysqli $mysqli, int $id): ?array
{
    $stmt = $mysqli->prepare(
        'SELECT id, titulo, descricao, data_evento, local_evento, situacao, cartaz
         FROM eventos WHERE id = ?'
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $evento = $stmt->get_result()->fetch_assoc();
    return $evento ?: null;
}

function criar_evento(mysqli $mysqli, array $dados): int
{
    $stmt = $mysqli->prepare(
        'INSERT INTO eventos
         (titulo, descricao, data_evento, local_evento, situacao, cartaz)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'ssssss',
        $dados['titulo'],
        $dados['descricao'],
        $dados['data_evento'],
        $dados['local_evento'],
        $dados['situacao'],
        $dados['cartaz']
    );
    $stmt->execute();
    return (int) $mysqli->insert_id;
}

function atualizar_evento(mysqli $mysqli, int $id, array $dados): void
{
    $stmt = $mysqli->prepare(
        'UPDATE eventos SET titulo = ?, descricao = ?, data_evento = ?,
         local_evento = ?, situacao = ?, cartaz = ? WHERE id = ?'
    );
    $stmt->bind_param(
        'ssssssi',
        $dados['titulo'],
        $dados['descricao'],
        $dados['data_evento'],
        $dados['local_evento'],
        $dados['situacao'],
        $dados['cartaz'],
        $id
    );
    $stmt->execute();
}

function excluir_evento(mysqli $mysqli, int $id): void
{
    $stmt = $mysqli->prepare('DELETE FROM eventos WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
