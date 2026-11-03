# API didática de Pontos de Coleta

Exemplo novo para a Aula 14: uma API REST pequena, sem dependência de rewrite, executável em PHP 8.2 + MySQLi + MariaDB 10.5. O diretório `publicar/` é o único conteúdo público; SQL, testes e documentação ficam fora do web root.

## Estrutura

- `banco/01_estrutura.sql` e `02_dados.sql`: tabela, índices, restrições e 6 registros didáticos;
- `publicar/index.html`: console visual da API;
- `publicar/api/index.php`: front controller;
- `publicar/api/includes/`: HTTP, validação, conexão, rota e repositório separados;
- `openapi.yaml`: contrato dos recursos, schemas e respostas;
- `testes/`: testes unitários, de integração MariaDB e HTTP em subpasta;
- `compose.yaml`: PHP 8.2/Apache + MariaDB 10.5 apenas para testes locais.

## Publicação em hospedagem compartilhada

1. No phpMyAdmin, selecione primeiro o banco fornecido pela hospedagem e importe, nesta ordem, `banco/01_estrutura.sql` e `banco/02_dados.sql`. Os scripts criam somente a tabela `pontos` e seus registros; eles não criam nem selecionam outro banco.
2. Envie **o conteúdo** de `publicar/` para uma subpasta pública, por exemplo `public_html/aula14/`.
3. Edite somente `publicar/api/config.php` no servidor:
   - substitua `SEU_HOST_MYSQL`, `SEU_USUARIO_MYSQL`, `SUA_SENHA_MYSQL` e `SEU_BANCO_MYSQL`;
   - troque obrigatoriamente `TROQUE_POR_UMA_CHAVE_LONGA_E_ALEATORIA` por uma chave longa e aleatória;
   - inclua em `cors_origens` apenas front-ends de outra origem que precisem chamar a API. O console servido na mesma pasta não precisa de CORS.
4. Abra `https://seu-dominio.example/aula14/`.

A base é detectada por `SCRIPT_NAME`; não é preciso informar a subpasta. A API usa `api/index.php?rota=/pontos` e funciona em nginx/Apache sem `.htaccess`, `mod_rewrite` ou URLs amigáveis. Não publique `banco/`, `testes/`, `compose.yaml` nem este README dentro do web root.

> Credenciais do phpMyAdmin/banco e a API key são coisas diferentes. Nunca use uma senha real no material, em slides ou no console compartilhado.

## Rotas e métodos

| Requisição lógica | Transporte sem rewrite | Resultado |
|---|---|---|
| `GET /pontos` | `GET api/index.php?rota=/pontos` | lista paginada |
| `GET /pontos/1` | `GET api/index.php?rota=/pontos/1` | um item |
| `POST /pontos` | `POST api/index.php?rota=/pontos` | cria |
| `PUT /pontos/1` | `PUT api/index.php?rota=/pontos/1` | substitui |
| `DELETE /pontos/1` | `DELETE api/index.php?rota=/pontos/1` | exclui |

Filtros de coleção: `tipo`, `bairro` (busca parcial), `ativo`; paginação: `pagina` (padrão 1) e `limite` (padrão 20, máximo 100).

Exemplo:

```bash
curl 'https://seu-dominio.example/aula14/api/index.php?rota=/pontos&tipo=eletronicos&ativo=1&pagina=1&limite=10'
```

Escritas exigem `X-API-Key` e JSON:

```bash
curl -i -X POST 'https://seu-dominio.example/aula14/api/index.php?rota=/pontos' \
  -H 'Content-Type: application/json' \
  -H 'X-API-Key: SUA_CHAVE_CONFIGURADA' \
  --data '{"nome":"Ecoponto da Escola","tipo":"reciclaveis","bairro":"Centro","endereco":"Rua da Aula, 14","latitude":-23.55052,"longitude":-46.633308,"ativo":true}'
```

Tipos aceitos: `eletronicos`, `oleo`, `pilhas`, `reciclaveis`, `medicamentos`.

## Contrato HTTP

- `200`: consulta ou atualização;
- `201 Created` + `Location`: criação;
- `204 No Content`: exclusão e preflight `OPTIONS`;
- `400`: paginação ou JSON malformado;
- `401` + `WWW-Authenticate`: chave ausente/incorreta em escrita;
- `404`: rota ou ID inexistente;
- `405` + `Allow`: método incompatível com a rota;
- `409`: duplicidade de nome + bairro;
- `415`: escrita com mídia diferente de `application/json`;
- `422`: filtro ou entidade semanticamente inválida;
- `500`: falha interna, com mensagem pública genérica.

Todas as respostas da API enviam `Cache-Control: no-store` e `X-Content-Type-Options: nosniff`. Erros são JSON no formato `{"erro":{"codigo":"...","mensagem":"..."}}`; detalhes internos vão somente ao log do servidor. CORS reflete apenas origens da allowlist. O `openapi.yaml` descreve as rotas REST lógicas; na hospedagem, traduza cada path para o parâmetro `rota` conforme a tabela acima.

## Console e DevTools

O console exibe método, URL, status, duração, headers e body. Abra também **DevTools → Network**, envie a requisição e selecione `index.php?rota=...` para conferir o que o navegador realmente transmitiu. Console é útil para erros JavaScript; logs de PHP/MariaDB/nginx não ficam disponíveis no navegador. A aba Network pode revelar a API key: não compartilhe capturas sem ocultá-la.

## Testes reproduzíveis

Pré-requisito: Docker com Compose. Na raiz:

```bash
chmod +x testes/run.sh
./testes/run.sh
```

O script cria banco limpo, testa o pacote montado em `/curso/aula14/`, executa lint PHP, 19 verificações unitárias, integração real com MariaDB 10.5 e 28 verificações HTTP, e remove os contêineres ao final. A porta local usada é `18080`.

Para explorar manualmente durante a suíte, comente temporariamente o `trap` de limpeza e abra `http://localhost:18080/curso/aula14/`.

## Limitações didáticas

- A API key única demonstra autenticação de serviço, mas não substitui usuários, permissões, expiração ou rotação de credenciais.
- Não há TLS no Docker; em produção, use HTTPS.
- `PUT` exige o objeto completo; `PATCH` não foi implementado.
- Paginação usa offset e é adequada ao volume didático, não a coleções enormes.
- Datas do MariaDB são devolvidas sem offset de fuso; o consumidor deve conhecer o fuso do servidor.
