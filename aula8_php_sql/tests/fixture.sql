CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  descricao VARCHAR(255) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  estoque INT NOT NULL DEFAULT 0,
  categoria_id INT NOT NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categorias (nome) VALUES ('Periféricos'), ('Áudio');
INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id) VALUES
('Teclado Mecânico', 'Teclas ABNT2 e iluminação branca', 199.90, 4, 1),
('Mouse Sem Fio', 'Conexão USB e bateria inclusa', 89.90, 12, 1),
('Headset USB', 'Microfone integrado e controle de volume', 249.90, 0, 2);
