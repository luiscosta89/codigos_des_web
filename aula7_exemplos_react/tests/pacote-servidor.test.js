import { describe, expect, it } from 'vitest';
import { access, readFile, readdir } from 'node:fs/promises';
import { extname, resolve } from 'node:path';
import { JSDOM } from 'jsdom';

const raizProjeto = resolve(import.meta.dirname, '..');
const pacoteServidor = resolve(raizProjeto, '..', 'AULA 7 - Pacote Servidor');

async function listarArquivos(diretorio) {
  const entradas = await readdir(diretorio, { withFileTypes: true });
  return (await Promise.all(entradas.map(async (entrada) => {
    const caminho = resolve(diretorio, entrada.name);
    return entrada.isDirectory() ? listarArquivos(caminho) : [caminho];
  }))).flat();
}

describe('pacote compatível com servidor restrito', () => {
  it('gera exemplos-react.html autônomo, sem arquivos externos', async () => {
    const html = await readFile(resolve(raizProjeto, 'standalone', 'exemplos-react.html'), 'utf8');

    expect(html).toContain('<style>');
    expect(html).toContain('<script>');
    expect(html).not.toMatch(/<script[^>]+src=/i);
    expect(html).not.toMatch(/<link[^>]+stylesheet/i);
  });

  it('não inclui módulos mjs nem arquivos de fonte no pacote do servidor', async () => {
    const arquivos = await listarArquivos(pacoteServidor);
    const proibidas = new Set(['.mjs', '.ttf', '.otf', '.woff', '.woff2']);
    const encontrados = arquivos.filter((arquivo) => proibidas.has(extname(arquivo).toLowerCase()));

    expect(encontrados).toEqual([]);
  });

  it('mantém a anatomia de projeto apresentada no slide 9', async () => {
    const obrigatorios = [
      'public/produtos.json',
      'src/main.jsx',
      'src/App.jsx',
      'src/index.css',
      'src/components/ProdutoCard.jsx',
      'index.html',
      'exemplos-react.html',
      'package.json',
      'vite.config.js',
      'dist/index.html',
      'dist/exemplos-react.html',
    ];

    await Promise.all(obrigatorios.map((arquivo) => expect(access(resolve(pacoteServidor, arquivo))).resolves.toBeUndefined()));
  });

  it('mostra conteúdo nos dois HTMLs mesmo quando JavaScript não executa', async () => {
    const index = new JSDOM(await readFile(resolve(pacoteServidor, 'index.html'), 'utf8'));
    const exemplos = new JSDOM(await readFile(resolve(pacoteServidor, 'exemplos-react.html'), 'utf8'));

    expect(index.window.document.body.textContent).toContain('TechStore');
    expect(index.window.document.body.textContent).toContain('Teclado Mecânico');
    expect(exemplos.window.document.body.textContent).toContain('Todos os exemplos React dos slides');
    expect(exemplos.window.document.body.textContent).toContain('Componente funcional');
  });
});
