import { build } from 'esbuild';
import { cp, mkdir, rm, writeFile } from 'node:fs/promises';

const destino = new URL('../standalone/', import.meta.url);
await rm(destino, { recursive: true, force: true });
await mkdir(destino, { recursive: true });

async function compilarPagina(entrada) {
  const resultado = await build({
    entryPoints: [entrada],
    bundle: true,
    format: 'iife',
    platform: 'browser',
    target: ['es2020'],
    jsx: 'automatic',
    write: false,
    outdir: 'saida-virtual',
    minify: true,
  });
  const javascript = resultado.outputFiles.find(({ path }) => path.endsWith('.js'))?.text;
  const css = resultado.outputFiles.find(({ path }) => path.endsWith('.css'))?.text;
  if (!javascript || !css) throw new Error(`Não foi possível compilar ${entrada}.`);
  return { javascript: javascript.replaceAll('</script', '<\\/script'), css };
}

function paginaHtml({ titulo, descricao, javascript, css, bodyId = '', fallback }) {
  return `<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="${descricao}" />
  <title>${titulo}</title>
  <style>${css}</style>
</head>
<body${bodyId ? ` id="${bodyId}"` : ''}>
  <noscript>Ative o JavaScript para executar os exemplos React.</noscript>
  <div id="root">${fallback}</div>
  <script>${javascript}</script>
</body>
</html>
`;
}

const app = await compilarPagina('src/main.jsx');
const exemplos = await compilarPagina('src/ExemplosSlides.jsx');

const fallbackApp = `<main class="container py-5">
  <section class="hero rounded-4 p-4 mb-4">
    <span class="eyebrow">AULA 7 · INTRODUÇÃO AO REACT</span>
    <h1>TechStore: estado vira interface</h1>
    <p>Versão estática de segurança. Com JavaScript habilitado, os controles React tornam-se interativos.</p>
    <a href="./exemplos-react.html">Abrir todos os exemplos React</a>
  </section>
  <section class="row g-3">
    <article class="col-md-4"><div class="card p-3"><h2 class="h5">Teclado Mecânico</h2><p>R$ 199,90</p><button>Adicionar</button></div></article>
    <article class="col-md-4"><div class="card p-3"><h2 class="h5">Mouse Sem Fio</h2><p>R$ 89,90</p><button>Adicionar</button></div></article>
    <article class="col-md-4"><div class="card p-3"><h2 class="h5">Headset Gamer</h2><p>R$ 249,90</p><button>Adicionar</button></div></article>
  </section>
</main>`;

const fallbackExemplos = `<main class="container py-5">
  <span class="eyebrow">AULA 7 · INTRODUÇÃO AO REACT</span>
  <h1>Todos os exemplos React dos slides</h1>
  <p>Código e resultado visual. Com JavaScript habilitado, os controles tornam-se interativos.</p>
  ${[
    ['01', 'Componente funcional', 'function ProdutoCard() { return <article>...</article>; }'],
    ['02', 'JSX e expressões', '<h2>{produto.nome}</h2>'],
    ['03', 'Props', 'function ProdutoCard({ nome, preco })'],
    ['04', 'map e key', 'produtos.map(produto => <ProdutoCard key={produto.id} />)'],
    ['05', 'Eventos', '<button onClick={executarAcao}>Adicionar</button>'],
    ['06', 'Feedback visual e toast', 'visivel && <div role="status">Produto adicionado</div>'],
    ['07', 'useState', 'const [contador, setContador] = useState(0)'],
    ['08', 'Estado imutável', 'setCarrinho(atual => [...atual, produto])'],
    ['09', 'Renderização condicional', 'itens.length === 0 ? <Vazio /> : <Carrinho />'],
    ['10', 'Formulário controlado', '<input value={busca} onChange={...} />'],
    ['11', 'Estado elevado', '<Resumo quantidade={quantidade} />'],
    ['12', 'useEffect e fetch', "useEffect(() => fetch('./produtos.json'), [])"],
    ['13', 'Bootstrap com className', '<button className="btn btn-primary">Adicionar</button>'],
  ].map(([numero, titulo, codigo]) => `<article class="example-section"><h2>${numero} · ${titulo}</h2><pre><code>${codigo.replaceAll('<', '&lt;').replaceAll('>', '&gt;')}</code></pre></article>`).join('')}
</main>`;

await writeFile('standalone/index.html', paginaHtml({
  titulo: 'Aula 7 — Exemplos React',
  descricao: 'Exemplos executáveis da Aula 7 — Introdução ao React',
  bodyId: 'topo',
  fallback: fallbackApp,
  ...app,
}), 'utf8');

await writeFile('standalone/exemplos-react.html', paginaHtml({
  titulo: 'Aula 7 — Todos os exemplos React',
  descricao: 'Galeria executável dos exemplos React da Aula 7',
  fallback: fallbackExemplos,
  ...exemplos,
}), 'utf8');

await cp('public/produtos.json', 'standalone/produtos.json');
await cp('public/exemplos', 'standalone/exemplos', { recursive: true });

console.log('index.html e exemplos-react.html autônomos criados sem fontes ou assets externos');
