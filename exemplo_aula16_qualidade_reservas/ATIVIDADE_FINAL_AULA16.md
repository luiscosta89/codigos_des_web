# Atividade final — Aula 16

## Objetivo

Adicionar ao **ReservaLab** um filtro de reservas por status, implementando a mudança de ponta a ponta com **Desenvolvimento Orientado a Testes (TDD)**.

A atividade exige alteração real do React, do cliente HTTP, da API PHP, do repositório e dos testes. Somente publicar ou executar o exemplo existente não atende à proposta.

## Incremento obrigatório

1. Adicione um `<select>` acessível com as opções:
   - Todas;
   - Pendente;
   - Confirmada;
   - Cancelada.
2. Ao selecionar um status, faça o cliente executar `GET api/index.php?status=...`.
3. Mantenha o GET sem parâmetro retornando todas as reservas.
4. Na API, normalize e valide o parâmetro. Valores diferentes de `pendente`, `confirmada` e `cancelada` devem retornar `422` em JSON.
5. No repositório, aplique o filtro por meio de **prepared statement**. Não concatene a entrada em SQL.
6. Preserve os estados de carregamento, erro, lista vazia e sucesso.

## Ciclos TDD mínimos

Execute cada comportamento como um ciclo separado:

1. **RED:** escreva o teste e confirme que ele falha pelo motivo esperado;
2. **GREEN:** implemente somente o necessário para fazê-lo passar;
3. **REFACTOR:** melhore o código mantendo a suíte verde;
4. execute a suíte completa antes do ciclo seguinte.

## Testes obrigatórios

- teste de componente: selecionar “Confirmada” dispara a nova consulta e atualiza a lista;
- teste do cliente HTTP: o status válido aparece codificado na query string;
- teste PHP unitário: status válido é aceito e status inválido é rejeitado;
- teste de integração: `?status=confirmada` retorna somente reservas confirmadas;
- teste de integração: status inválido retorna `422`;
- regressão: o GET sem filtro continua retornando todas as reservas.

## Critérios de aceite

| Cenário | Resultado esperado |
|---|---|
| GET sem `status` | todas as reservas |
| `status=confirmada` | somente reservas confirmadas |
| `status=pendente` | somente reservas pendentes |
| `status=cancelada` | somente reservas canceladas |
| status válido sem correspondências | `200` e lista vazia |
| status não permitido | `422` e erro em JSON |

## Entrega

- projeto fonte alterado, sem `node_modules/`;
- evidência dos testes em RED e depois em GREEN;
- resultado final de `npm run quality`;
- resultado final do smoke test atualizado;
- captura do DevTools Network mostrando a query string, sem expor a API key;
- nova build gerada com `npm run build`.

Para publicar a atualização, envie conjuntamente o novo `dist/index.html` e todo o conteúdo de `dist/assets/`. Preserve o `api/config.php` já configurado no servidor.
