<?php
declare(strict_types=1);

function listEquipment(mysqli $db): array
{
    $result = $db->query('SELECT id, nome, patrimonio, disponivel FROM equipamentos_a16 ORDER BY nome');
    return array_map(static function (array $row): array {
        $row['id'] = (int) $row['id'];
        $row['disponivel'] = (bool) $row['disponivel'];
        return $row;
    }, $result->fetch_all(MYSQLI_ASSOC));
}

function listReservations(mysqli $db): array
{
    $sql = 'SELECT r.id, r.equipamento_id, e.nome AS equipamento_nome, r.solicitante, r.inicio, r.fim, r.status
            FROM reservas_a16 r INNER JOIN equipamentos_a16 e ON e.id = r.equipamento_id
            ORDER BY r.inicio, r.id';
    $rows = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    foreach ($rows as &$row) { $row['id'] = (int) $row['id']; $row['equipamento_id'] = (int) $row['equipamento_id']; }
    return $rows;
}

function createReservationInDatabase(mysqli $db, array $input): array
{
    $db->begin_transaction();
    try {
        $equipmentId = (int) $input['equipamento_id'];
        $equipment = $db->prepare('SELECT id FROM equipamentos_a16 WHERE id = ? AND disponivel = 1 FOR UPDATE');
        $equipment->bind_param('i', $equipmentId);
        $equipment->execute();
        if ($equipment->get_result()->num_rows !== 1) { throw new DomainException('Equipamento inexistente ou indisponível.'); }

        $overlap = $db->prepare("SELECT id FROM reservas_a16 WHERE equipamento_id = ? AND status <> 'cancelada' AND inicio < ? AND fim > ? LIMIT 1 FOR UPDATE");
        $overlap->bind_param('iss', $equipmentId, $input['fim'], $input['inicio']);
        $overlap->execute();
        if ($overlap->get_result()->num_rows > 0) { throw new DomainException('O equipamento já possui reserva nesse período.'); }

        $insert = $db->prepare("INSERT INTO reservas_a16 (equipamento_id, solicitante, inicio, fim, status) VALUES (?, ?, ?, ?, 'confirmada')");
        $insert->bind_param('isss', $equipmentId, $input['solicitante'], $input['inicio'], $input['fim']);
        $insert->execute();
        $id = $db->insert_id;
        $db->commit();
        return ['id' => $id, 'status' => 'confirmada'];
    } catch (Throwable $error) {
        $db->rollback();
        throw $error;
    }
}
