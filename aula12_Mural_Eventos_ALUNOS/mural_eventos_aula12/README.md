# Mural de Eventos Acadêmicos — Aula 12

Exemplo didático em PHP 8.2, MySQLi e MariaDB para praticar:

- autenticação com `password_verify()` e sessões;
- CRUD completo de eventos;
- consultas preparadas;
- validação no servidor;
- upload de cartazes em PDF, PNG ou JPG/JPEG;
- publicação da aplicação em uma subpasta do servidor.

## Credencial da aplicação

Depois de importar os dados iniciais, o login do **Mural de Eventos** é:

```text
Usuário: admin
Senha: aula12
```

Essa credencial é diferente do usuário e da senha do banco de dados.

## Instalação no servidor da disciplina

### 1. Importar o banco no phpMyAdmin

Selecione o banco atribuído ao seu usuário e importe, nesta ordem:

1. `banco/01_estrutura.sql`;
2. `banco/02_dados.sql`.

A importação cria as tabelas `usuarios` e `eventos`, o usuário `admin` e três eventos de exemplo.

### 2. Publicar os arquivos PHP

Crie uma pasta para a aplicação no servidor. Por exemplo:

```text
mural-eventos
```

Envie **o conteúdo da pasta `publicar/`** para ela. A estrutura publicada deve ficar assim:

```text
mural-eventos/
├── assets/
├── eventos/
├── includes/
├── uploads/
├── index.php
├── login.php
├── logout.php
└── painel.php
```

Não publique as pastas `banco/` e `tests/` dentro da área acessível pelo navegador.

### 3. Informar as credenciais do banco

Edite somente:

```text
mural-eventos/includes/config.php
```

Substitua os três valores abaixo pelos dados fornecidos para seu banco no servidor da disciplina:

```php
'usuario' => getenv('DB_USER') ?: 'SEU_USUARIO',
'senha' => getenv('DB_PASS') ?: 'SUA_SENHA',
'banco' => getenv('DB_NAME') ?: 'SEU_BANCO',
```

Exemplo ilustrativo:

```php
'usuario' => getenv('DB_USER') ?: 'aluno123',
'senha' => getenv('DB_PASS') ?: 'senha-fornecida-pelo-servidor',
'banco' => getenv('DB_NAME') ?: 'aluno123',
```

Não copie os valores do exemplo. Use suas próprias credenciais do MariaDB/phpMyAdmin.

O host padrão é:

```php
'host' => getenv('DB_HOST') ?: 'localhost',
```

Altere o host somente se o servidor da disciplina fornecer outro endereço.

A URL base é detectada automaticamente. Normalmente não é necessário editar `base_url`.

> Não envie seu `config.php` com credenciais reais para GitHub, fóruns ou grupos públicos.

### 4. Conferir a pasta de uploads

O pacote inclui:

```text
uploads/index.html
```

Isso garante que a pasta `uploads/` seja criada durante a extração. O PHP também tenta criá-la automaticamente caso esteja ausente.

Se o servidor informar que a pasta não permite gravação, ajuste a permissão de `uploads/` pelo gerenciador de arquivos. No ambiente didático, use `0777` se `0775` não funcionar.

### 5. Abrir a aplicação

Abra a URL correspondente à pasta publicada. Por exemplo:

```text
https://servidor.exemplo/mural-eventos/
```

Entre com:

```text
admin / aula12
```

## Como testar o exemplo

1. Entre com uma senha incorreta para observar a validação.
2. Entre com `admin` e `aula12`.
3. Cadastre um evento como `Rascunho`, `Publicado` ou `Finalizado`.
4. Envie um cartaz em PDF, PNG ou JPG/JPEG com até 2 MB.
5. Confira no phpMyAdmin o nome gravado na coluna `eventos.cartaz`.
6. Confira o arquivo físico na pasta `uploads/`.
7. Edite o evento e troque o cartaz.
8. Exclua o evento e confirme a remoção do registro e do arquivo.
9. Saia do sistema e tente acessar novamente o painel.

Imagens aparecem como miniaturas no painel. Arquivos PDF aparecem como links.

## Relação entre banco e sistema de arquivos

O banco não armazena o conteúdo da imagem ou do PDF. A coluna `eventos.cartaz` guarda somente um nome gerado pelo servidor, por exemplo:

```text
7ca91a73cbed35f2.png
```

O arquivo correspondente fica em:

```text
uploads/7ca91a73cbed35f2.png
```

## Formatos e limites

- PDF;
- PNG;
- JPG/JPEG;
- máximo de 2 MB;
- validação da extensão e do conteúdo MIME;
- nome aleatório gerado pelo servidor.

## Requisitos do servidor

- PHP 8.2 ou compatível;
- extensão MySQLi;
- extensão Fileinfo;
- MariaDB ou MySQL;
- uploads habilitados no PHP;
- permissão de escrita na pasta `uploads/`.

## Segurança e escopo didático

O projeto utiliza consultas preparadas, escape de HTML, hash de senha, proteção de sessão e validação básica de uploads. CSRF, políticas de conteúdo, cabeçalhos de segurança e endurecimento avançado da pasta de uploads são assuntos da aula de Segurança Web.
