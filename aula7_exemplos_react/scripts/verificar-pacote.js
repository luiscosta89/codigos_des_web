import { JSDOM, VirtualConsole } from 'jsdom';
import { pathToFileURL } from 'node:url';
import { resolve } from 'node:path';

const arquivo = resolve(process.argv[2] ?? 'standalone/index.html');
const erros = [];
const virtualConsole = new VirtualConsole();
virtualConsole.on('jsdomError', (erro) => erros.push(erro.message));
const dom = await JSDOM.fromFile(arquivo, {
  url: pathToFileURL(arquivo).href,
  resources: 'usable',
  runScripts: 'dangerously',
  pretendToBeVisual: true,
  virtualConsole,
});
await new Promise((resolveWait) => setTimeout(resolveWait, 700));
const texto = dom.window.document.body.textContent;
const galeria = arquivo.endsWith('exemplos-react.html');
const esperado = galeria
  ? ['Todos os exemplos React dos slides', 'Componente funcional', 'Bootstrap com className']
  : ['TechStore: estado vira interface', 'Teclado Mecânico'];
if (erros.length || esperado.some((trecho) => !texto.includes(trecho))) {
  console.error({ arquivo, erros, trecho: texto.slice(0, 300) });
  process.exitCode = 1;
} else {
  console.log(JSON.stringify({ arquivo, abriuPorFile: true, conteudoVerificado: esperado }));
}
dom.window.close();
