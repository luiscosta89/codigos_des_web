# Aula 16 — Testes, depuração e qualidade de aplicações web

Exemplo completo de **reservas de equipamentos acadêmicos**. O front-end React/Vite lista equipamentos e reservas e cria uma reserva. A API PHP 8.2/MySQLi faz leitura pública e protege escrita com `X-API-Key`. MariaDB 10.5 guarda `equipamentos_a16` e `reservas_a16` em relação 1:N.

## Requisitos

- Node.js 22.12+ e npm;
- Docker com Compose para o smoke test;
- para publicação: hospedagem PHP 8.2, extensão MySQLi e uma base MariaDB 10.5 já provisionada.

Não há credenciais reais. `api/config.php` contém placeholders. A chave digitada na interface permanece apenas no estado em memória do React; não vai para `localStorage`, bundle ou banco.

## TDD estrito: ciclo da aula

1. escreva um teste pequeno para um comportamento;
2. execute e confirme **RED** pela ausência/incorreção daquele comportamento;
3. implemente somente o necessário;
4. execute e confirme **GREEN**;
5. refatore mantendo a suíte verde.

As fatias deste exemplo cobrem: funções puras de período/payload, cliente HTTP, componente/fluxo React, validação PHP pura e integração HTTP+banco. `api/tests/run.php` não depende de PHPUnit. A sobreposição usa a regra `novo.inicio < existente.fim AND novo.fim > existente.inicio`; períodos adjacentes são permitidos.

```bash
npm install
npm test                 # Vitest: funções JS, cliente API e componentes
npm run test:coverage    # relatório texto + coverage/index.html
npm run test:php         # requer php local; alternativa abaixo
npm run lint
npm run build
npm run quality          # lint + test + build
npm run smoke            # build deve existir; PHP 8.2 + MariaDB 10.5
```

Sem PHP local:

```bash
docker run --rm -v "$PWD/api:/app" -w /app php:8.2-cli php tests/run.php
```

## Contrato da API

A URL do cliente é construída literalmente com:

```js
new URL('api/index.php', document.baseURI)
```

- `GET api/index.php` — público; `200` com `{ "equipamentos": [...], "reservas": [...] }`.
- `POST api/index.php` — exige `Content-Type: application/json` e `X-API-Key`.
- corpo: `equipamento_id` inteiro, `solicitante`, `inicio` e `fim` em `YYYY-MM-DD HH:MM:SS`.
- respostas relevantes: `201`, `400` JSON inválido, `401` chave inválida, `405`, `409` indisponibilidade/sobreposição, `415` mídia diferente de JSON, `422` validação e `500` envelope seguro.

A validação temporal está em funções puras (`api/src/validation.php`). Os valores de `datetime-local` são interpretados no fuso institucional definido por `timezone` em `api/config.php` — por padrão, `America/Sao_Paulo`. A integração abre transação, bloqueia o equipamento com `FOR UPDATE`, consulta conflito no banco e insere antes do commit. As `CHECK`, FK, índice e unicidade exata complementam a proteção na camada de dados. Exceções técnicas vão para `error_log`; a resposta pública não expõe SQL, senha, caminho ou stack trace.

## Banco

1. No painel/phpMyAdmin, selecione a base **já criada pela hospedagem**.
2. Importe `sql/schema.sql`.
3. Importe `sql/seed.sql`.

Os scripts distribuídos não contêm `CREATE DATABASE`, `DROP DATABASE` nem `USE`. Tabelas e constraints levam o sufixo exclusivo `a16`, evitando colisões com outras aulas.

## Publicação em subpasta

1. Edite localmente ou no editor privado do servidor apenas `api/config.php`: host, porta, nome da base, usuário, senha, `timezone` e `api_key`.
2. Gere uma chave no servidor com `openssl rand -hex 32`; não a publique nem a embuta no React.
3. Rode `npm run build`. O Vite usa `base: './'`, e o script copia `api/` para `dist/api/` sem `api/tests/`.
4. Crie a subpasta pública, por exemplo `public_html/campus/aula16/`.
5. Envie **o conteúdo de `dist/`**, de forma que `index.html`, `assets/` e `api/` fiquem diretamente nessa subpasta. Não envie a pasta `dist` como nível adicional.
6. Em atualizações, substitua juntos `index.html` e todo `assets/`; preserve o `api/config.php` do servidor se ele já contém a configuração privada correta.

A mesma build funciona em caminhos aninhados porque assets usam `./` e a API parte de `document.baseURI`.

## Diagnóstico pelo navegador

Abra DevTools (`F12`). Não suponha acesso a logs PHP, MariaDB, Apache ou Nginx: em hospedagem compartilhada eles podem não estar disponíveis ao aluno.

### Network

1. Recarregue a página e filtre por `index.php`.
2. Confira URL (deve permanecer na subpasta), método, status e `Content-Type: application/json`.
3. Em GET, inspecione a resposta pública e confirme os dois arrays.
4. Em POST, confira `Request Headers` (`X-API-Key`) e o payload JSON. **Não compartilhe capturas que revelem a chave.**
5. `401` aponta para chave ausente/diferente; `422`, campos/período; `409`, equipamento indisponível ou choque; `500`, falha interna/configuração. Se a resposta for uma página HTML do provedor, o cliente mostra apenas “Falha HTTP N”; o HTML completo fica no Network.

### Console

Use para erros JavaScript, importação de assets, CSP e promises rejeitadas. Um erro de rede costuma ter evidência mais útil em Network. O Console do navegador não mostra logs internos do PHP ou do MariaDB.

## Smoke test integrado

`npm run smoke` sobe MariaDB 10.5 e uma imagem baseada em PHP 8.2/Apache com MySQLi. A build é montada em `/campus/departamento/aula16`, comprovando caminho aninhado. O script verifica página, GET público, autenticação, tipo de mídia (`415`), JSON inválido (`400`), período inválido (`422`), método (`405`), criação (`201`), sobreposição (`409`) e total final no banco; os contêineres e volumes são removidos ao sair.

## Atividade final — não implementada no exemplo

Adicione um **filtro de reservas por status de ponta a ponta**, seguindo RED → GREEN → REFACTOR. Esta funcionalidade não está pronta e deve ser entregue pelo aluno.

Critérios obrigatórios:

1. um `<select>` React com “todos”, `pendente`, `confirmada` e `cancelada`;
2. o cliente envia `?status=...` no GET sem alterar o POST;
3. a API valida a query string e devolve `422` para valor não permitido;
4. o repositório filtra com parâmetro preparado, sem concatenar entrada em SQL;
5. testes Vitest para interação do select e URL do cliente;
6. testes PHP para a validação do status;
7. smoke test comprovando filtro válido e rejeição do inválido;
8. `npm run quality` e `npm run smoke` verdes.

Comece pelo primeiro teste que falha. Não acrescente somente um filtro visual sobre dados já baixados: a aceitação exige query string, validação API, repositório e testes.

Consulte [MAPA_PROJETO.md](MAPA_PROJETO.md) para o mapa literal.
