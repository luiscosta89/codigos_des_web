import { cp, mkdir, rm, writeFile } from 'node:fs/promises';
import { extname } from 'node:path';

const projeto = new URL('../', import.meta.url);
const destino = new URL('../../AULA 7 - Pacote Servidor/', import.meta.url);
const proibidas = new Set(['.mjs', '.ttf', '.otf', '.woff', '.woff2']);

await rm(destino, { recursive: true, force: true });
await mkdir(destino, { recursive: true });

await cp(projeto, destino, {
  recursive: true,
  filter: (origem) => {
    const normalizado = origem.replaceAll('\\', '/');
    if (normalizado.includes('/node_modules/') || normalizado.endsWith('/node_modules')) return false;
    if (normalizado.includes('/standalone/') || normalizado.endsWith('/standalone')) return false;
    return !proibidas.has(extname(normalizado).toLowerCase());
  },
});

await cp(new URL('standalone/index.html', projeto), new URL('dist/index.html', destino));
await cp(new URL('standalone/exemplos-react.html', projeto), new URL('dist/exemplos-react.html', destino));

await writeFile(new URL('LEIA-ME.txt', destino), `AULA 7 — PROJETO REACT + PUBLICAÇÃO

A estrutura segue a anatomia apresentada nos slides:
- public/: arquivos servidos diretamente;
- src/: código React da aplicação;
- src/main.jsx: ponto de entrada;
- src/App.jsx: componente principal;
- src/index.css: estilos globais;
- src/components/: componentes da TechStore;
- index.html: entrada do Vite;
- package.json: scripts e dependências;
- dist/: versão compilada para o servidor.

DESENVOLVIMENTO LOCAL
1. npm install
2. npm run dev
3. Abra o endereço localhost mostrado pelo Vite.

PUBLICAÇÃO NO SERVIDOR
Envie TODO O CONTEÚDO INTERNO de dist/ para a pasta pública do servidor.
O servidor não executa src/ nem JSX; ele somente entrega o resultado compilado de dist/.

EXEMPLOS DOS SLIDES
- Durante o desenvolvimento: http://localhost:5173/exemplos-react.html
- No servidor: exemplos-react.html, depois de enviar o conteúdo de dist/.
- Por duplo clique: o exemplos-react.html da raiz encaminha para dist/exemplos-react.html.

COMPATIBILIDADE
- Nenhum arquivo .mjs.
- Nenhum arquivo de fonte.
- dist/exemplos-react.html e dist/index.html são autônomos.
`, 'utf8');

console.log('Pacote criado com a estrutura dos slides, sem .mjs e sem fontes');
