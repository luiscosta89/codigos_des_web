<?php

declare(strict_types=1);

final class DominioDuplicadoException extends RuntimeException
{
}

final class RepositorioPontos
{
    public function __construct(private mysqli $db)
    {
    }

    public function listar(array $filtros, int $limite, int $deslocamento): array
    {
        $condicoes = [];
        $parametros = [];
        $tipos = '';

        if (isset($filtros['tipo'])) {
            $condicoes[] = 'tipo = ?';
            $parametros[] = $filtros['tipo'];
            $tipos .= 's';
        }
        if (isset($filtros['bairro'])) {
            $condicoes[] = 'bairro LIKE ?';
            $parametros[] = '%' . $filtros['bairro'] . '%';
            $tipos .= 's';
        }
        if (isset($filtros['ativo'])) {
            $condicoes[] = 'ativo = ?';
            $parametros[] = $filtros['ativo'] ? 1 : 0;
            $tipos .= 'i';
        }

        $where = $condicoes === [] ? '' : ' WHERE ' . implode(' AND ', $condicoes);
        $sql = 'SELECT id, nome, tipo, bairro, endereco, latitude, longitude, ativo, criado_em, atualizado_em'
            . ' FROM pontos' . $where . ' ORDER BY id LIMIT ? OFFSET ?';
        $parametros[] = $limite;
        $parametros[] = $deslocamento;
        $tipos .= 'ii';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($tipos, ...$parametros);
        $stmt->execute();
        return array_map([$this, 'normalizar'], $stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }

    public function contar(array $filtros): int
    {
        $condicoes = [];
        $parametros = [];
        $tipos = '';
        if (isset($filtros['tipo'])) {
            $condicoes[] = 'tipo = ?';
            $parametros[] = $filtros['tipo'];
            $tipos .= 's';
        }
        if (isset($filtros['bairro'])) {
            $condicoes[] = 'bairro LIKE ?';
            $parametros[] = '%' . $filtros['bairro'] . '%';
            $tipos .= 's';
        }
        if (isset($filtros['ativo'])) {
            $condicoes[] = 'ativo = ?';
            $parametros[] = $filtros['ativo'] ? 1 : 0;
            $tipos .= 'i';
        }
        $sql = 'SELECT COUNT(*) AS total FROM pontos'
            . ($condicoes === [] ? '' : ' WHERE ' . implode(' AND ', $condicoes));
        $stmt = $this->db->prepare($sql);
        if ($tipos !== '') {
            $stmt->bind_param($tipos, ...$parametros);
        }
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nome, tipo, bairro, endereco, latitude, longitude, ativo, criado_em, atualizado_em'
            . ' FROM pontos WHERE id = ?'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_assoc();
        return $linha === null ? null : $this->normalizar($linha);
    }

    public function criar(array $ponto): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pontos (nome, tipo, bairro, endereco, latitude, longitude, ativo) VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $ativo = $ponto['ativo'] ? 1 : 0;
        $stmt->bind_param(
            'ssssddi',
            $ponto['nome'],
            $ponto['tipo'],
            $ponto['bairro'],
            $ponto['endereco'],
            $ponto['latitude'],
            $ponto['longitude'],
            $ativo
        );
        try {
            $stmt->execute();
        } catch (mysqli_sql_exception $erro) {
            if ($erro->getCode() === 1062) {
                throw new DominioDuplicadoException('Ponto já cadastrado.', 0, $erro);
            }
            throw $erro;
        }
        return (int) $this->db->insert_id;
    }

    public function atualizar(int $id, array $ponto): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE pontos SET nome = ?, tipo = ?, bairro = ?, endereco = ?, latitude = ?, longitude = ?, ativo = ? WHERE id = ?'
        );
        $ativo = $ponto['ativo'] ? 1 : 0;
        $stmt->bind_param(
            'ssssddii',
            $ponto['nome'],
            $ponto['tipo'],
            $ponto['bairro'],
            $ponto['endereco'],
            $ponto['latitude'],
            $ponto['longitude'],
            $ativo,
            $id
        );
        try {
            $stmt->execute();
        } catch (mysqli_sql_exception $erro) {
            if ($erro->getCode() === 1062) {
                throw new DominioDuplicadoException('Ponto já cadastrado.', 0, $erro);
            }
            throw $erro;
        }
        return $stmt->affected_rows > 0 || $this->buscar($id) !== null;
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pontos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    private function normalizar(array $linha): array
    {
        $linha['id'] = (int) $linha['id'];
        $linha['latitude'] = (float) $linha['latitude'];
        $linha['longitude'] = (float) $linha['longitude'];
        $linha['ativo'] = (bool) $linha['ativo'];
        return $linha;
    }
}
