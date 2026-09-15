# Aula 7 — Exemplos executáveis de React

Projeto complementar à apresentação **AULA 7 — Introdução ao React**. A aplicação usa o mesmo caso TechStore das aulas de JavaScript e Bootstrap.

## Executar

```bash
npm install
npm run dev
```

Abra o endereço exibido pelo Vite. Para gerar uma versão estática:

```bash
npm run build
npm run preview
```

Para publicar em Apache, Nginx, cPanel ou outro servidor externo, consulte `DEPLOY_SERVIDOR.md`. A compilação usa caminhos relativos e funciona na raiz do domínio ou em subpastas.

O projeto mantém a mesma anatomia mostrada no slide 9. No servidor, publique o conteúdo interno de `dist/`; `src/` continua sendo o código React estudado em aula. O pacote de servidor não contém `.mjs` nem arquivos de fonte.

## Testar

```bash
npm test
```

## Relação com os slides

- Slides 6 e 20: comparação imperativa/declarativa e ciclo de atualização.
- Slides 7, 13 e 25: árvore de componentes e estado elevado em `src/App.jsx`.
- Slides 10–12: componente e JSX em `src/components/ProdutoCard.jsx`.
- Slides 14–15: props e callback entre `ListaProdutos` e `ProdutoCard`.
- Slides 16–17: `map` e `key` em `ListaProdutos.jsx`; botão para reordenar produtos.
- Slides 18–22: evento, `useState` e atualização imutável do carrinho.
- Slide 23: renderização condicional em `Carrinho.jsx`.
- Slide 24: formulário controlado em `BuscaProduto.jsx`.
- Slide 26: `useEffect` e `fetch('/produtos.json')` em `App.jsx`.
- Slide 27: classes Bootstrap aplicadas com `className`.
- Slide 28: labels, botões semânticos, `aria-live`, estados e mensagens de erro.

A comparação em HTML/JavaScript puro fica em:

- `public/exemplos/dom-imperativo.html`

## Estrutura

```text
index.html
exemplos-react.html
public/
  produtos.json
  exemplos/dom-imperativo.html
src/
  main.jsx
  App.jsx
  index.css
  components/
  domain/
tests/
dist/               # build moderno para servidor HTTP/HTTPS
standalone/          # versão compilada que também abre por duplo clique
```
