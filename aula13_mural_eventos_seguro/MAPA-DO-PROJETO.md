# Mapa entre a Aula 13 e o projeto executável

| Slides | Conceito | Arquivos principais |
|---|---|---|
| 4–5 | Fronteiras de confiança e riscos | `publicar/includes/bootstrap.php`, `README.md` |
| 6–9 | Autenticação, autorização e isolamento por proprietário | `includes/autenticacao.php`, `includes/repositorio_eventos.php`, `banco/01_estrutura.sql` |
| 10–12 | SQL Injection e consultas preparadas | `includes/repositorio_usuarios.php`, `includes/repositorio_eventos.php` |
| 13–15 | XSS e codificação da saída | `includes/funcoes.php`, `painel.php`, `eventos/formulario.php` |
| 16–18 | CSRF | `includes/funcoes.php`, `login.php`, `logout.php`, `eventos/salvar.php`, `eventos/excluir.php` |
| 19–21 | Cookie, sessão, senha, timeout e logout | `includes/bootstrap.php`, `includes/autenticacao.php`, `login.php`, `logout.php` |
| 22–24 | Política e entrega privada de uploads | `eventos/salvar.php`, `cartaz.php`, `uploads/.htaccess` |
| 25 | Erros públicos e logs | `includes/bootstrap.php` |
| 26 | Configuração e segredos | `includes/config.php` |
| 27 | Cabeçalhos HTTP | `includes/funcoes.php`, função `cabecalhos_seguranca()` |
| 28–29 | Publicação e verificação | `README.md`, `tests/run.sh`, `tests/http_test.py` |

## Evidências automatizadas

- `tests/unit.php`: CSRF, validação, IDs, upload, configuração, URL base, cabeçalhos e timeout.
- `tests/integracao.php`: instalação limpa, hashes e autorização no MariaDB.
- `tests/http_test.py`: login, CSRF, cookies, CRUD, IDOR, XSS, upload, headers, traversal e logout.
- `tests/run.sh`: prepara o ambiente Docker, executa toda a suíte e remove os contêineres.
