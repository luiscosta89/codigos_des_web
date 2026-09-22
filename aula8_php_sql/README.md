# TechStore — PHP + SQL

Projeto didático da Aula 8 de Desenvolvimento para Web. Ele mostra um catálogo **somente leitura** usando PHP 8.2, MySQLi e MariaDB 10.5 — o mesmo conjunto exibido no painel dos alunos.

## O que o projeto demonstra

- arquivos PHP organizados em diretórios;
- banco preparado pelo phpMyAdmin;
- conexão com MariaDB usando a extensão `mysqli`;
- `SELECT`, `JOIN`, filtro `LIKE` e prepared statement;
- saída segura com `htmlspecialchars`;
- catálogo responsivo e busca por produto ou categoria.

> Cadastro, edição, exclusão, autenticação e upload não fazem parte deste projeto. Esses tópicos ficam para a aula posterior.

## Estrutura

```text
techstore-php-sql/
├── publicar/                     # somente o conteúdo desta pasta vai para a URL
│   ├── index.php
│   ├── config.php                # preencher com as credenciais do aluno
│   ├── assets/css/style.css
│   └── includes/
│       ├── conexao.php
│       ├── funcoes.php
│       └── repositorio_produtos.php
├── banco/                        # importar pelo phpMyAdmin; não publicar
│   ├── 01_schema.sql
│   └── 02_dados.sql
├── tests/                        # testes usados para validar o material
├── MAPA-DO-PROJETO.md
└── README.md
```

## 1. Preparar o banco no phpMyAdmin

1. Na coluna esquerda, clique no banco que corresponde ao seu usuário. **Não crie outro banco e não execute `CREATE DATABASE`.**
2. Abra a aba **Importar** e envie `banco/01_schema.sql`.
3. Importe `banco/02_dados.sql`.
4. Abra a aba **SQL** e confirme:

```sql
SELECT p.nome, p.preco, p.estoque, c.nome AS categoria
FROM produtos p
JOIN categorias c ON c.id = p.categoria_id
ORDER BY p.nome;
```

## 2. Configurar a conexão

Edite `publicar/config.php`:

```php
return [
    'host' => 'localhost',
    'porta' => 3306,
    'banco' => 'NOME_DO_BANCO_DO_ALUNO',
    'usuario' => 'USUARIO_DO_ALUNO',
    'senha' => 'SENHA_DO_ALUNO',
];
```

O nome do banco e o usuário devem ser copiados exatamente do painel da disciplina. Não compartilhe a senha.

## 3. Publicar

1. Crie uma pasta como `techstore` no diretório web pessoal.
2. Envie **o conteúdo de `publicar/`** para essa pasta.
3. Acesse a URL correspondente, por exemplo: `https://servidor/.../techstore/`.
4. Pesquise `mouse`, `áudio` ou `periféricos`.

A pasta `banco/` fica no computador do aluno e é usada somente pelo phpMyAdmin. Não envie backups `.sql` para um diretório público.

## Compatibilidade observada no painel

- PHP 8.2.33;
- extensão MySQLi;
- MariaDB 10.5.29;
- phpMyAdmin 5.2.3;
- Nginx 1.20.1;
- `utf8mb4_unicode_ci` para as tabelas do projeto.

Os avisos amarelos do phpMyAdmin sobre chave de cookies, diretório temporário e armazenamento de configuração pertencem à instalação do servidor. Eles não impedem a execução deste projeto básico.

## Testes do material

O projeto foi validado em contêineres com PHP 8.2 + MySQLi e MariaDB 10.5. Os testes cobrem funções, consultas reais e a página HTTP com filtro.
