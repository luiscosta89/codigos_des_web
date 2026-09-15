import { describe, expect, it } from 'vitest';
import { JSDOM, VirtualConsole } from 'jsdom';
import { pathToFileURL } from 'node:url';
import { resolve } from 'node:path';

describe('pacote que abre por duplo clique', () => {
  it('renderiza a TechStore ao abrir index.html por file://', async () => {
    const erros = [];
    const virtualConsole = new VirtualConsole();
    virtualConsole.on('jsdomError', (erro) => erros.push(erro.message));

    const arquivo = resolve('standalone/index.html');
    const dom = await JSDOM.fromFile(arquivo, {
      url: pathToFileURL(arquivo).href,
      resources: 'usable',
      runScripts: 'dangerously',
      pretendToBeVisual: true,
      virtualConsole,
    });

    await new Promise((resolveWait) => setTimeout(resolveWait, 500));

    expect(erros).toEqual([]);
    expect(dom.window.document.body.textContent).toContain('TechStore: estado vira interface');
    expect(dom.window.document.body.textContent).toContain('Teclado Mecânico');
    dom.window.close();
  });
});
