# Guia de demonstração — Aula 13

## Preparação

1. Importe `banco/01_estrutura.sql` e `banco/02_dados.sql` em uma base limpa.
2. Publique o conteúdo de `publicar/` em uma subpasta.
3. Preencha apenas `publicar/includes/config.php`.
4. Entre como `ana / aula13` em uma janela e `bruno / aula13` em outra janela anônima.

## Sequência sugerida

1. **Autorização:** mostre que Ana vê dois eventos. Copie o ID de um evento de Ana e tente acessá-lo como Bruno; a resposta deve ser 404.
2. **SQL parametrizado:** abra `repositorio_usuarios.php` e `repositorio_eventos.php`; destaque `prepare()`, `bind_param()` e o filtro por `usuario_id`.
3. **XSS:** cadastre um título com caracteres `<`, `>`, aspas e `&`; mostre que o texto aparece codificado, sem ser interpretado como marcação.
4. **CSRF:** abra o HTML do formulário, remova ou altere o campo `csrf_token` nas ferramentas do navegador e tente salvar; a resposta deve ser 403 e o banco deve permanecer igual.
5. **Sessão:** inspecione o cookie e mostre `HttpOnly` e `SameSite=Lax`; em HTTPS, mostre também `Secure`.
6. **Upload:** envie uma imagem válida e mostre o `.bin` em `uploads/` e o MIME no banco. Depois envie um PDF e mostre que `cartaz.php` força download.
7. **Erros:** mostre que mensagens públicas não exibem SQL, caminhos ou credenciais.
8. **Cabeçalhos:** use a aba Network ou `curl -I` para observar CSP, `nosniff`, `DENY`, `Referrer-Policy` e `no-store`.
9. **Logout:** confirme que `GET /logout.php` retorna 405 e que o formulário POST com CSRF encerra a sessão.

## Fechamento

Execute `./tests/run.sh` e relacione os resultados com o checklist do slide 28. A suíte deve terminar com 11 testes unitários, 6 testes de integração, 7 cenários HTTP, 2 usuários, 4 eventos e nenhum upload residual.
