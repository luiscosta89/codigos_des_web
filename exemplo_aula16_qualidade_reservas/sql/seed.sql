INSERT INTO equipamentos_a16 (nome, patrimonio, disponivel) VALUES
('Projetor multimídia A', 'A16-PROJ-001', 1),
('Kit de robótica', 'A16-ROBO-002', 1),
('Câmera DSLR', 'A16-CAM-003', 0);

INSERT INTO reservas_a16 (equipamento_id, solicitante, inicio, fim, status) VALUES
(1, 'Laboratório de Física', '2030-03-18 09:00:00', '2030-03-18 11:00:00', 'confirmada'),
(2, 'Projeto Integrador', '2030-03-19 14:00:00', '2030-03-19 16:00:00', 'pendente');
