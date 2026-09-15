# Publicação em servidor externo

A aplicação foi configurada com caminhos relativos. Ela pode ser publicada na raiz do domínio ou em qualquer subpasta, sem alterar o código.

Exemplos de endereço:

- `https://meuservidor.com/aula7/`
- `https://meuservidor.com/alunos/ana/react/`
- `https://meuservidor.com.br/~usuario/techstore/`

## Opção 1 — servidor apenas para arquivos estáticos

No computador de desenvolvimento:

```bash
npm install
npm run build
```

Envie **todo o conteúdo da pasta `dist/`** para uma pasta pública do servidor. Preserve as subpastas `assets/` e `exemplos/`.

Depois, abra a URL correspondente à pasta que contém `index.html`.

Não é necessário instalar React, Node.js ou npm no servidor. O navegador recebe arquivos HTML, CSS, JavaScript e JSON já compilados.

## Opção 2 — servidor com Node.js e acesso ao terminal

Envie o projeto completo, exceto `node_modules`, e execute:

```bash
npm ci
npm run build
```

Configure Apache, Nginx ou o painel de hospedagem para servir a pasta `dist/` como diretório público.

> `npm run preview` serve apenas para conferência. Não deve ser usado como servidor de produção permanente.

## Apache ou cPanel

O pacote inclui `dist/.htaccess`. Em hospedagens Apache, envie esse arquivo junto com os demais. Ele define o documento inicial, tipos MIME e cabeçalhos básicos.

Como a aula não usa React Router, nenhuma regra de redirecionamento é necessária.

## Nginx

Exemplo para uma publicação em `/aula7/`:

```nginx
location /aula7/ {
    alias /var/www/aula7/;
    index index.html;
    try_files $uri $uri/ =404;
}
```

Ajuste apenas o caminho físico e o endereço público de acordo com o servidor.

## Permissões usuais

- pastas: `755`;
- arquivos: `644`;
- `index.html` deve estar diretamente na pasta acessada pela URL.

## Conferência após o envio

1. Abra `index.html` pela URL HTTP ou HTTPS — não por `file://`.
2. Confirme que os seis produtos aparecem.
3. Pesquise por “mouse”.
4. Adicione um produto e confira badge, total e toast.
5. Abra `exemplos/dom-imperativo.html`.
6. Abra `exemplos-react.html` para consultar os exemplos dos slides.
7. Verifique no console do navegador se não há erros 404.

## Problemas comuns

- **Página sem estilo:** a pasta `assets/` não foi enviada ou sua estrutura foi alterada.
- **Produtos não aparecem:** `produtos.json` está ausente, sem permissão de leitura ou com nome diferente.
- **Erro de MIME em JavaScript:** use o `.htaccess` incluído ou configure `.js` como `application/javascript`.
- **Funciona na raiz, mas não na subpasta:** publique esta versão atualizada, que usa `base: './'` e resolve os dados a partir da URL do próprio `index.html`.
- **Página em branco por duplo clique:** use os HTMLs da raiz deste pacote; eles encaminham para as versões autônomas em `dist/`.

Este pacote não usa arquivos `.mjs` nem arquivos de fonte. O servidor precisa aceitar apenas HTML, CSS, JavaScript e JSON.
