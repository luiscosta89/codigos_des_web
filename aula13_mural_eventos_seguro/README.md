# Mural de Eventos Acadêmicos — Aula 13 (versão protegida)

Exemplo **didático** em PHP 8.2, MySQLi e MariaDB. A Aula 13 parte do CRUD da Aula 12 e acrescenta proteção contra CSRF, isolamento por proprietário, sessão endurecida, cabeçalhos HTTP, tratamento seguro de erros e entrega privada de uploads. Não é um sistema de produção.

## Instalação limpa

### 1. Banco de dados no phpMyAdmin

Selecione o banco fornecido pela disciplina e importe, nesta ordem:

1. `banco/01_estrutura.sql`;
2. `banco/02_dados.sql`.

Uma instalação limpa contém **2 usuários e 4 eventos**: dois eventos de Ana e dois de Bruno. A coluna `eventos.usuario_id` é uma chave estrangeira para `usuarios.id`.

### 2. Publicação

Envie **o conteúdo de `publicar/`**, não a pasta `publicar` em si, para a pasta pública escolhida no servidor. Não publique `banco/` nem `tests/`.

Estrutura esperada:

```text
mural-eventos/
├── assets/
├── eventos/
├── includes/
├── uploads/
│   ├── .htaccess
│   └── index.html
├── cartaz.php
├── index.php
├── login.php
├── logout.php
└── painel.php
```

### 3. Único arquivo de ambiente a editar

Edite somente `includes/config.php`. Substitua os três placeholders pelos dados do MariaDB/phpMyAdmin da disciplina:

```php
'usuario' => getenv('DB_USER') ?: 'SEU_USUARIO',
'senha' => getenv('DB_PASS') ?: 'SUA_SENHA',
'banco' => getenv('DB_NAME') ?: 'SEU_BANCO',
```

Essas são credenciais **do banco**, não do Mural. O sistema interrompe a inicialização com uma mensagem controlada enquanto algum placeholder continuar ativo. O host padrão é `localhost`; altere-o apenas se a hospedagem indicar outro host.

A URL base é calculada pelo script em execução e funciona na raiz ou em subpastas. Em uso normal não edite `base_url`. Nunca publique credenciais reais.

### 4. Pasta de uploads

`uploads/index.html` garante que a pasta exista no pacote. O usuário do PHP precisa conseguir gravar nela. Prefira `0775` quando a propriedade/grupo estiverem corretos; use permissão mais ampla apenas como diagnóstico temporário no servidor didático.

Os arquivos recebem nomes aleatórios como `9fd...c2.bin`; a extensão original nunca é usada no disco. O MIME validado fica em `eventos.cartaz_mime`. A aplicação entrega cartazes somente por `cartaz.php`, após autenticação e autorização: imagens são `inline`; PDF é `attachment`.

`uploads/.htaccess` bloqueia acesso direto no Apache. **nginx ignora `.htaccess`**: em nginx, o administrador deve negar a rota `/uploads/` na configuração do servidor ou, idealmente, apontar o diretório de uploads para fora da raiz pública. Os nomes aleatórios reduzem exposição acidental, mas não substituem essa regra do servidor.

## Contas do aplicativo

As contas didáticas iniciais são:

```text
ana / aula13
bruno / aula13
```

As credenciais não aparecem na tela de login. Cada usuário vê, pesquisa, altera, exclui e baixa apenas os próprios eventos e cartazes.

## O que mudou em relação à Aula 12

- token CSRF de 32 bytes em login, criação/edição, exclusão e logout;
- logout somente por `POST`;
- sessão com `strict_mode`, cookie `HttpOnly`, `SameSite=Lax`, `Secure` em HTTPS, novo ID após login e timeout de 30 minutos;
- autorização por `usuario_id` diretamente em todas as consultas; recurso alheio retorna 404;
- CSP, `nosniff`, `Referrer-Policy`, `X-Frame-Options` e `Cache-Control: no-store` centralizados;
- erros públicos genéricos com referência aleatória, sem exibir detalhes internos;
- validação de IDs positivos, limite de 5000 caracteres e saída HTML escapada;
- upload limitado a 2 MB, PDF/PNG/JPG/JPEG, Fileinfo, `getimagesize` e 6000 × 6000 px;
- nomes `.bin` aleatórios, MIME no banco, entrega autorizada por `cartaz.php` e limpeza em substituição/exclusão/falha;
- links em nova aba usam `rel="noopener noreferrer"`.

## Roteiro manual defensivo

1. Entre como Ana e confirme que aparecem somente dois eventos dela.
2. Pesquise um título de Bruno e confirme resultado vazio.
3. Crie e edite um evento, primeiro sem cartaz e depois com imagem/PDF.
4. Cause um erro de validação com um arquivo escolhido; o formulário deve avisar que é preciso selecioná-lo novamente.
5. Tente arquivo com extensão permitida e conteúdo incompatível, arquivo maior que 2 MB e imagem acima de 6000 × 6000 px.
6. Abra uma imagem pelo painel e confirme a miniatura; o PDF deve baixar como anexo.
7. Exclua o evento e confira que registro e `.bin` foram removidos.
8. Saia pelo botão; `GET /logout.php` deve responder 405.
9. Em outra conta, tente a URL numérica do evento/cartaz anterior; a resposta deve ser 404.
10. Confira no navegador os cabeçalhos de segurança e os atributos do cookie de sessão.

## Testes automatizados

Requer Docker, Python 3, curl e as imagens `techstore-php-test:latest` e `mariadb:10.5`.

Na raiz do projeto:

```bash
chmod +x tests/run.sh
./tests/run.sh
```

A suíte executa:

- lint de todos os arquivos PHP;
- 11 testes unitários de CSRF, validação, IDs, upload, configuração, URL base, cabeçalhos e timeout;
- 6 testes de integração real com MariaDB para contagens, seeds e autorização no repositório;
- 7 cenários HTTP cobrindo login, CSRF, cookies, regeneração da sessão, logout, CRUD, busca/IDOR, XSS, upload de imagem/PDF, substituição e limpeza de órfãos, headers e traversal.

O script cria banco e servidor temporários, valida ao final `usuarios=2` e `eventos=4` e remove containers, rede e arquivos de teste mesmo quando ocorre falha.

## Requisitos

- PHP 8.2 ou compatível, com MySQLi, mbstring e Fileinfo;
- MariaDB 10.5 ou MySQL compatível;
- uploads PHP habilitados;
- HTTPS na publicação real para que o cookie receba `Secure`;
- permissão de gravação em `uploads/`;
- regra equivalente ao `.htaccess` se o servidor for nginx.
