# Banco da Aula 16

As tabelas desta aula têm estrutura diferente das aulas anteriores:

- `equipamentos_a16`: catálogo com patrimônio único e disponibilidade;
- `reservas_a16`: reservas relacionadas por chave estrangeira em cardinalidade 1:N.

## Importação

1. Selecione no phpMyAdmin o banco já provisionado pela hospedagem.
2. Importe `schema.sql`.
3. Importe `seed.sql`.

Os scripts não contêm `CREATE DATABASE`, `DROP DATABASE` nem `USE`. Eles não modificam `pontos`, `pontos_novo` ou qualquer tabela das aulas 14 e 15.

A relação é:

```text
equipamentos_a16 (1) ───── (N) reservas_a16
```

O schema inclui chave estrangeira, índices, `CHECK` de período/status e unicidade do período exato por equipamento.
