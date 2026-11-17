# Publicação da Aula 16

## 1. Banco de dados

No phpMyAdmin, selecione o banco já fornecido pela hospedagem. Não crie outro banco.

Importe, nesta ordem:

1. `sql/schema.sql` — cria `equipamentos_a16` e `reservas_a16`;
2. `sql/seed.sql` — insere três equipamentos e duas reservas didáticas.

Os nomes das tabelas, índices, chaves e constraints possuem identificação da Aula 16 e não alteram as tabelas das aulas anteriores.

## 2. Configuração privada

Edite somente `api/config.php` diretamente no ambiente privado/local ou no editor do servidor. Substitua os placeholders do banco, confirme o `timezone` institucional e informe uma chave de escrita própria.

Não envie credenciais ou chaves em mensagens, slides, repositórios ou capturas de tela.

## 3. Primeira publicação

O pacote direto é destinado à **primeira instalação**. Não extraia novamente o pacote completo sobre uma aplicação já configurada, pois isso substituiria `api/config.php` pelos placeholders.

Execute no projeto fonte:

```bash
npm ci
npm run quality
npm run build
```

Envie o **conteúdo de `dist/`** para a pasta pública da Aula 16. A estrutura final deve ser:

```text
aula16/
├── index.html
├── assets/
└── api/
    ├── config.php
    ├── index.php
    └── src/
```

Acesse a URL da pasta, por exemplo `https://servidor/curso/aula16/`.

## 4. Atualizações do frontend

Depois de uma nova build, substitua juntos:

- `index.html`;
- todo o conteúdo de `assets/`.

Preserve `api/config.php`. Somente substitua `api/` quando o código do backend tiver sido alterado, mantendo o arquivo de configuração privado do servidor.

## 5. Diagnóstico

Use DevTools → Network para verificar URL, método, status, cabeçalhos e resposta JSON. O Console é adequado para erros JavaScript e carregamento de assets, mas não exibe logs internos do PHP, MariaDB, Apache ou Nginx.
