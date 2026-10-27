<?php
declare(strict_types=1);

function listar_eventos(mysqli $mysqli, int $usuarioId, string $busca = ''): array
{
    $termo = '%' . $busca . '%';
    $stmt = $mysqli->prepare(
        'SELECT id, usuario_id, titulo, descricao, data_evento, local_evento, situacao, cartaz, cartaz_mime
         FROM eventos
         WHERE usuario_id = ? AND (? = \'\' OR titulo LIKE ? OR descricao LIKE ? OR local_evento LIKE ?)
         ORDER BY data_evento, titulo'
    );
    $stmt->bind_param('issss', $usuarioId, $busca, $termo, $termo, $termo);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function buscar_evento(mysqli $mysqli, int $id, int $usuarioId): ?array
{
    $stmt = $mysqli->prepare(
        'SELECT id, usuario_id, titulo, descricao, data_evento, local_evento, situacao, cartaz, cartaz_mime
         FROM eventos WHERE id = ? AND usuario_id = ?'
    );
    $stmt->bind_param('ii', $id, $usuarioId);
    $stmt->execute();
    $evento = $stmt->get_result()->fetch_assoc();
    return $evento ?: null;
}

function criar_evento(mysqli $mysqli, int $usuarioId, array $dados): int
{
    $stmt = $mysqli->prepare(
        'INSERT INTO eventos (usuario_id, titulo, descricao, data_evento, local_evento, situacao, cartaz, cartaz_mime)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('isssssss', $usuarioId, $dados['titulo'], $dados['descricao'], $dados['data_evento'],
        $dados['local_evento'], $dados['situacao'], $dados['cartaz'], $dados['cartaz_mime']);
    $stmt->execute();
    return (int) $mysqli->insert_id;
}

function atualizar_evento(mysqli $mysqli, int $id, int $usuarioId, array $dados): bool
{
    $stmt = $mysqli->prepare(
        'UPDATE eventos SET titulo=?, descricao=?, data_evento=?, local_evento=?, situacao=?, cartaz=?, cartaz_mime=?
         WHERE id=? AND usuario_id=?'
    );
    $stmt->bind_param('sssssssii', $dados['titulo'], $dados['descricao'], $dados['data_evento'], $dados['local_evento'],
        $dados['situacao'], $dados['cartaz'], $dados['cartaz_mime'], $id, $usuarioId);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

function excluir_evento(mysqli $mysqli, int $id, int $usuarioId): bool
{
    $stmt = $mysqli->prepare('DELETE FROM eventos WHERE id = ? AND usuario_id = ?');
    $stmt->bind_param('ii', $id, $usuarioId);
    $stmt->execute();
    return $stmt->affected_rows === 1;
}
