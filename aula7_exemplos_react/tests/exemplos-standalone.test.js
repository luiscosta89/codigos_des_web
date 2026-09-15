import { describe, expect, it } from 'vitest';
import { JSDOM, VirtualConsole } from 'jsdom';
import { pathToFileURL } from 'node:url';
import { resolve } from 'node:path';

describe('galeria standalone dos exemplos dos slides', () => {
  it('abre por file://, lista os conceitos e executa uma interação de estado', async () => {
    const erros = [];
    const virtualConsole = new VirtualConsole();
    virtualConsole.on('jsdomError', (erro) => erros.push(erro.message));
    const arquivo = resolve('standalone/exemplos-react.html');
    const dom = await JSDOM.fromFile(arquivo, {
      url: pathToFileURL(arquivo).href,
      resources: 'usable',
      runScripts: 'dangerously',
      pretendToBeVisual: true,
      virtualConsole,
    });
    await new Promise((resolveWait) => setTimeout(resolveWait, 700));

    const texto = dom.window.document.body.textContent;
    for (const conceito of ['Componente funcional', 'JSX e expressões', 'Props', 'map e key', 'Eventos', 'Feedback visual e toast', 'useState', 'Estado imutável', 'Renderização condicional', 'Formulário controlado', 'Estado elevado', 'useEffect', 'Bootstrap com className']) {
      expect(texto).toContain(conceito);
    }

    const botao = [...dom.window.document.querySelectorAll('button')]
      .find((elemento) => elemento.textContent.includes('Incrementar'));
    botao.click();
    await new Promise((resolveWait) => setTimeout(resolveWait, 50));
    expect(dom.window.document.body.textContent).toContain('Contador: 1');
    expect(erros).toEqual([]);
    dom.window.close();
  });
});
