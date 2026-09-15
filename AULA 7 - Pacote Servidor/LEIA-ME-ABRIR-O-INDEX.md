# Como abrir o projeto

## Projeto-fonte com Vite

O `index.html` da raiz é uma entrada de desenvolvimento. Execute:

```bash
npm install
npm run dev
```

## Abrir por duplo clique

Execute uma vez:

```bash
npm run build:standalone
```

Depois abra:

```text
standalone/index.html
```

Esse arquivo utiliza JavaScript já compilado e dados incorporados, por isso também funciona com endereço `file://`.

## Publicar no servidor

Para um servidor HTTP/HTTPS moderno:

```bash
npm run build
```

Envie o conteúdo de `dist/`.

Também é possível enviar o conteúdo de `standalone/`; essa versão funciona tanto no servidor quanto por duplo clique.
