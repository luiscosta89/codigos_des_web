# Mapa do projeto

| Arquivo | Responsabilidade | Slides relacionados |
|---|---|---|
| `publicar/index.php` | recebe `?busca=`, chama a consulta e monta o catálogo | 5, 8, 12, 13, 23, 24 |
| `publicar/config.php` | contém os dados específicos do banco do aluno | 7, 22, 25, 26 |
| `publicar/includes/conexao.php` | abre a conexão MySQLi e ativa `utf8mb4` | 22 |
| `publicar/includes/funcoes.php` | normaliza busca, formata preço/estoque e escapa HTML | 9, 10, 11 |
| `publicar/includes/repositorio_produtos.php` | executa o `SELECT` preparado com `JOIN` | 19, 23, 24, 27 |
| `publicar/assets/css/style.css` | define o visual responsivo da TechStore | 8, 25 |
| `banco/01_schema.sql` | cria `categorias` e `produtos` | 14, 15, 16 |
| `banco/02_dados.sql` | insere os dados didáticos | 17 |

## Limite desta aula

A aplicação apenas lista e filtra produtos. `INSERT`, `UPDATE`, `DELETE`, autenticação e upload via PHP serão implementados na aula posterior.
