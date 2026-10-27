-- Senha didática de ambos os usuários: aula13
INSERT INTO usuarios (id, nome, login, senha) VALUES
(1, 'Ana Martins', 'ana', '$2y$10$Q9ohQqH0fq8RGqM1ffJdBeH9S2DPqDIVyHkwIj6w08bgTMD.4McN2'),
(2, 'Bruno Souza', 'bruno', '$2y$10$Q9ohQqH0fq8RGqM1ffJdBeH9S2DPqDIVyHkwIj6w08bgTMD.4McN2');

INSERT INTO eventos (usuario_id, titulo, descricao, data_evento, local_evento, situacao, cartaz, cartaz_mime) VALUES
(1, 'Semana Acadêmica de Tecnologia', 'Palestras e oficinas sobre desenvolvimento, dados e inovação.', '2026-11-12', 'Auditório Central', 'Publicado', NULL, NULL),
(1, 'Oficina de Git e GitHub', 'Atividade prática de versionamento para projetos em equipe.', '2026-11-18', 'Laboratório 2', 'Publicado', NULL, NULL),
(2, 'Mostra de Projetos Web', 'Apresentação dos projetos desenvolvidos pelos estudantes.', '2026-12-01', 'Laboratório 3', 'Rascunho', NULL, NULL),
(2, 'Encontro de Pesquisa', 'Roda de conversa sobre iniciação científica e extensão.', '2026-12-08', 'Sala Multiuso', 'Finalizado', NULL, NULL);
