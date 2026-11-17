CREATE TABLE equipamentos_a16 (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(120) NOT NULL,
    patrimonio VARCHAR(40) NOT NULL,
    disponivel TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    CONSTRAINT uq_equipamentos_a16_patrimonio UNIQUE (patrimonio),
    CONSTRAINT chk_equipamentos_a16_disponivel CHECK (disponivel IN (0, 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservas_a16 (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    equipamento_id INT UNSIGNED NOT NULL,
    solicitante VARCHAR(120) NOT NULL,
    inicio DATETIME NOT NULL,
    fim DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'confirmada',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_reservas_a16_equipamento_periodo (equipamento_id, inicio, fim),
    CONSTRAINT fk_reservas_a16_equipamentos_a16 FOREIGN KEY (equipamento_id)
        REFERENCES equipamentos_a16 (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT chk_reservas_a16_periodo CHECK (fim > inicio),
    CONSTRAINT chk_reservas_a16_status CHECK (status IN ('pendente', 'confirmada', 'cancelada')),
    CONSTRAINT uq_reservas_a16_periodo_exato UNIQUE (equipamento_id, inicio, fim)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
