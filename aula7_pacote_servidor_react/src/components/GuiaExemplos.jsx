import { resolverAsset } from '../domain/caminhos.js';

const exemplos = [
  {
    titulo: '1. Componente funcional e JSX',
    slides: 'Slides 10–12',
    codigo: `function ProdutoCard() {\n  return <article className="card">Produto</article>;\n}`,
  },
  {
    titulo: '2. Props: dados e callback',
    slides: 'Slides 14–15',
    codigo: `<ProdutoCard\n  produto={produto}\n  onAdicionar={adicionar}\n/>`,
  },
  {
    titulo: '3. Lista com map e key',
    slides: 'Slides 16–17',
    codigo: `{produtos.map(produto => (\n  <ProdutoCard key={produto.id} produto={produto} />\n))}`,
  },
  {
    titulo: '4. Evento e estado',
    slides: 'Slides 18–21',
    codigo: `const [carrinho, setCarrinho] = useState([]);\nsetCarrinho(atual => [...atual, produto]);`,
  },
  {
    titulo: '5. Renderização condicional',
    slides: 'Slide 23',
    codigo: `{itens.length === 0\n  ? <p>Seu carrinho está vazio.</p>\n  : <ListaCarrinho itens={itens} />}`,
  },
  {
    titulo: '6. Formulário controlado',
    slides: 'Slide 24',
    codigo: `<input\n  value={busca}\n  onChange={evento => setBusca(evento.target.value)}\n/>`,
  },
  {
    titulo: '7. Estado elevado',
    slides: 'Slide 25',
    codigo: `App → guarda carrinho\nNavbar ← quantidade\nListaProdutos → onAdicionar\nCarrinho ← itens`,
  },
  {
    titulo: '8. Efeito e fetch',
    slides: 'Slide 26',
    codigo: `useEffect(() => {\n  fetch('/produtos.json')\n    .then(r => r.json())\n    .then(setProdutos);\n}, []);`,
  },
  {
    titulo: '9. Bootstrap em JSX',
    slides: 'Slide 27',
    codigo: `<article className="card h-100">\n  <button className="btn btn-primary">Adicionar</button>\n</article>`,
  },
];

export default function GuiaExemplos() {
  return (
    <section className="mt-5" aria-labelledby="titulo-guia">
      <span className="eyebrow">CÓDIGO DOS SLIDES</span>
      <h2 id="titulo-guia" className="h3">Exemplos executados nesta página</h2>
      <p className="text-secondary">Abra cada item para relacionar o trecho didático com a implementação funcional acima.</p>
      <div className="accordion-grid">
        {exemplos.map((exemplo) => (
          <details className="example-card" key={exemplo.titulo}>
            <summary>
              <span>{exemplo.titulo}</span>
              <small>{exemplo.slides}</small>
            </summary>
            <pre><code>{exemplo.codigo}</code></pre>
          </details>
        ))}
      </div>
      <p className="mt-3 mb-0">
        <a href={resolverAsset('exemplos-react.html')}>Abrir a galeria completa de exemplos React</a><br />
        <a href={resolverAsset('exemplos/dom-imperativo.html')}>Abrir a versão imperativa em HTML puro</a> para comparar com o modelo declarativo do React.
      </p>
    </section>
  );
}
