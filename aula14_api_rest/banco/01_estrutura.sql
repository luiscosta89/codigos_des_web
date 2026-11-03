CREATE TABLE IF NOT EXISTS pontos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    tipo ENUM('eletronicos', 'oleo', 'pilhas', 'reciclaveis', 'medicamentos') NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    endereco VARCHAR(150) NOT NULL,
    latitude DECIMAL(9, 6) NOT NULL,
    longitude DECIMAL(9, 6) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_ponto_nome_bairro (nome, bairro),
    KEY idx_pontos_tipo (tipo),
    KEY idx_pontos_bairro (bairro),
    KEY idx_pontos_ativo (ativo),
    CONSTRAINT chk_pontos_latitude CHECK (latitude BETWEEN -90 AND 90),
    CONSTRAINT chk_pontos_longitude CHECK (longitude BETWEEN -180 AND 180)
) ENGINE=InnoDB;
