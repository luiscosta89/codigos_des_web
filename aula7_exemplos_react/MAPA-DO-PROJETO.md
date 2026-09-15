# Mapa do projeto — TechStore React

A pasta foi organizada conforme a anatomia apresentada no slide 9.

```text
techstore-react/
├── public/                     # arquivos servidos diretamente
│   ├── produtos.json
│   ├── exemplos/
│   │   └── dom-imperativo.html
├── src/                        # código da aplicação
│   ├── main.jsx                # ponto de entrada
│   ├── App.jsx                 # componente principal
│   ├── index.css               # estilos globais
│   ├── components/
│   │   ├── Navbar.jsx
│   │   ├── BuscaProduto.jsx
│   │   ├── ListaProdutos.jsx
│   │   ├── ProdutoCard.jsx
│   │   ├── Carrinho.jsx
│   │   ├── Feedback.jsx
│   │   └── GuiaExemplos.jsx
│   └── domain/
│       ├── carrinho.js
│       ├── produtos.js
│       └── caminhos.js
├── index.html                  # documento de entrada usado pelo Vite
├── exemplos-react.html         # segunda entrada: galeria dos slides
├── package.json                # scripts e dependências
├── package-lock.json           # versões instaladas
├── vite.config.js              # configuração do Vite
├── tests/                      # testes automatizados
└── dist/                       # resultado pronto para o servidor
    ├── index.html
    ├── exemplos-react.html
    ├── assets/
    ├── produtos.json
    └── exemplos/
```

## Testar no localhost

Na raiz do projeto:

```bash
npm install
npm run dev
```

Abra o endereço localhost mostrado pelo Vite.

## Testar no servidor da disciplina

Na raiz do projeto:

```bash
npm install
npm run build
```

Depois, envie **o conteúdo interno de `dist/`** para a pasta pública do servidor.

Não envie somente `src/` para um servidor estático: os arquivos JSX precisam ser processados pelo Vite. A pasta `dist/` já contém HTML, CSS e JavaScript compatíveis com o navegador.

O pacote não contém arquivos `.mjs` nem arquivos de fonte. Para abrir por duplo clique, `index.html` e `exemplos-react.html` encaminham automaticamente para suas versões compiladas em `dist/`.
