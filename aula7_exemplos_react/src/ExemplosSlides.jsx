import { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import 'bootstrap/dist/css/bootstrap.min.css';
import './index.css';
import './exemplos.css';
import { adicionarAoCarrinho, removerDoCarrinho } from './domain/carrinho.js';
import { reordenarProdutos } from './domain/produtos.js';

const produtosBase = [
  { id: 1, nome: 'Teclado Mecânico', preco: 199.9 },
  { id: 2, nome: 'Mouse Sem Fio', preco: 89.9 },
  { id: 3, nome: 'Headset Gamer', preco: 249.9 },
];

const moeda = (valor) => valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

function Bloco({ numero, titulo, slides, codigo, children }) {
  return (
    <article className="example-section">
      <header className="example-heading">
        <span className="example-number">{numero}</span>
        <div><h2>{titulo}</h2><small>{slides}</small></div>
      </header>
      <div className="example-columns">
        <pre><code>{codigo}</code></pre>
        <div className="live-demo"><span className="live-label">RESULTADO EXECUTÁVEL</span>{children}</div>
      </div>
    </article>
  );
}

function ProdutoSimples() {
  return <article className="mini-product"><strong>Teclado Mecânico</strong><span>R$ 199,90</span></article>;
}

function ProdutoComProps({ nome, preco }) {
  return <article className="mini-product"><strong>{nome}</strong><span>{moeda(preco)}</span></article>;
}

function ExemploLista() {
  const [produtos, setProdutos] = useState(produtosBase);
  return <><div className="mini-list">{produtos.map((produto) => <ProdutoComProps key={produto.id} {...produto} />)}</div><button className="btn btn-sm btn-outline-primary mt-3" onClick={() => setProdutos((atuais) => reordenarProdutos(atuais))}>Reordenar mantendo as keys</button></>;
}

function ExemploEvento() {
  const [mensagem, setMensagem] = useState('Nenhum clique recebido.');
  return <><button className="btn btn-primary" onClick={() => setMensagem('Evento recebido pela função!')}>Disparar onClick</button><p className="mt-3 mb-0" role="status">{mensagem}</p></>;
}

function ExemploFeedback() {
  const [visivel, setVisivel] = useState(false);
  return <><button className="btn btn-primary" onClick={() => setVisivel(true)}>Adicionar produto</button>{visivel && <div className="alert alert-success mt-3 mb-0" role="status">Produto adicionado com sucesso!</div>}</>;
}

function ExemploEstado() {
  const [contador, setContador] = useState(0);
  return <><p className="fs-5 fw-bold">Contador: {contador}</p><button className="btn btn-primary" onClick={() => setContador((atual) => atual + 1)}>Incrementar</button></>;
}

function ExemploImutavel() {
  const [itens, setItens] = useState([]);
  const produto = produtosBase[0];
  return <><p>Itens: {itens.length}</p><div className="d-flex gap-2"><button className="btn btn-sm btn-success" onClick={() => setItens((atuais) => adicionarAoCarrinho(atuais, produto))}>Criar novo array + adicionar</button><button className="btn btn-sm btn-outline-danger" disabled={!itens.length} onClick={() => setItens((atuais) => removerDoCarrinho(atuais, 0))}>Remover com filter</button></div></>;
}

function ExemploCondicional() {
  const [itens, setItens] = useState([]);
  return <><p className="alert alert-light">{itens.length === 0 ? 'Seu carrinho está vazio.' : `${itens.length} produto no carrinho.`}</p><button className="btn btn-sm btn-primary" onClick={() => setItens((atuais) => atuais.length ? [] : [produtosBase[0]])}>Alternar estado</button></>;
}

function ExemploFormulario() {
  const [busca, setBusca] = useState('');
  const visiveis = produtosBase.filter(({ nome }) => nome.toLowerCase().includes(busca.toLowerCase()));
  return <><label htmlFor="busca-demo" className="form-label">Buscar produto</label><input id="busca-demo" className="form-control" value={busca} onChange={(evento) => setBusca(evento.target.value)} placeholder="Digite mouse" /><p className="mt-2 mb-0">Resultado: {visiveis.map(({ nome }) => nome).join(', ') || 'nenhum'}</p></>;
}

function BotaoFilho({ onAdicionar }) {
  return <button className="btn btn-sm btn-primary" onClick={onAdicionar}>Filho emite ação</button>;
}
function ResumoFilho({ quantidade }) {
  return <span className="badge text-bg-success">Outro filho lê: {quantidade}</span>;
}
function ExemploEstadoElevado() {
  const [quantidade, setQuantidade] = useState(0);
  return <div className="d-flex flex-wrap align-items-center gap-3"><BotaoFilho onAdicionar={() => setQuantidade((atual) => atual + 1)} /><ResumoFilho quantidade={quantidade} /></div>;
}

function ExemploEfeito() {
  const [estado, setEstado] = useState('Aguardando o efeito…');
  useEffect(() => {
    const id = setTimeout(() => setEstado('Dados sincronizados após a renderização.'), 50);
    return () => clearTimeout(id);
  }, []);
  return <p className="alert alert-info mb-0">{estado}</p>;
}

function GaleriaExemplos() {
  const produto = { nome: 'Teclado Mecânico', preco: 199.9, estoque: 4 };
  return (
    <>
      <header className="examples-hero">
        <div className="container py-5">
          <span className="eyebrow">AULA 7 · INTRODUÇÃO AO REACT</span>
          <h1 className="display-5 fw-bold">Todos os exemplos React dos slides</h1>
          <p className="lead">Código à esquerda e resultado executável à direita. Use os controles para observar a interface reagir ao estado.</p>
          <a className="btn btn-dark" href="./index.html">Voltar à TechStore completa</a>
        </div>
      </header>
      <main className="container py-4 examples-stack">
        <Bloco numero="01" titulo="Componente funcional" slides="Slides 7, 10 e 13–14" codigo={`function ProdutoCard() {\n  return (\n    <article>\n      <h2>Teclado Mecânico</h2>\n      <p>R$ 199,90</p>\n    </article>\n  );\n}`}><ProdutoSimples /></Bloco>
        <Bloco numero="02" titulo="JSX e expressões" slides="Slides 11–12" codigo={`const produto = { nome: 'Teclado', preco: 199.9 };\n\n<h2>{produto.nome}</h2>\n<p>{produto.preco.toFixed(2)}</p>`}><ProdutoComProps nome={produto.nome} preco={produto.preco} /><p className="mt-2 mb-0">Estoque calculado no JSX: {produto.estoque > 0 ? `${produto.estoque} unidades` : 'indisponível'}</p></Bloco>
        <Bloco numero="03" titulo="Props" slides="Slide 15" codigo={`function ProdutoCard({ nome, preco }) {\n  return <p>{nome}: {preco}</p>;\n}\n\n<ProdutoCard nome="Mouse" preco={89.9} />`}><ProdutoComProps nome="Mouse Sem Fio" preco={89.9} /></Bloco>
        <Bloco numero="04" titulo="map e key" slides="Slides 16–17" codigo={`{produtos.map(produto => (\n  <ProdutoCard\n    key={produto.id}\n    produto={produto}\n  />\n))}`}><ExemploLista /></Bloco>
        <Bloco numero="05" titulo="Eventos" slides="Slide 18" codigo={`<button onClick={() => executarAcao()}>\n  Disparar evento\n</button>`}><ExemploEvento /></Bloco>
        <Bloco numero="06" titulo="Feedback visual e toast" slides="Slides 18, 23 e 27" codigo={`const [visivel, setVisivel] = useState(false);\n\n<button onClick={() => setVisivel(true)}>Adicionar</button>\n{visivel && <div role="status">\n  Produto adicionado com sucesso!\n</div>`}><ExemploFeedback /></Bloco>
        <Bloco numero="07" titulo="useState" slides="Slides 19–20" codigo={`const [contador, setContador] = useState(0);\n\n<button onClick={() => setContador(atual => atual + 1)}>\n  Incrementar\n</button>`}><ExemploEstado /></Bloco>
        <Bloco numero="08" titulo="Estado imutável" slides="Slides 21–22" codigo={`setCarrinho(atual => [...atual, produto]);\n\nsetCarrinho(atual =>\n  atual.filter((_, indice) => indice !== 0)\n);`}><ExemploImutavel /></Bloco>
        <Bloco numero="09" titulo="Renderização condicional" slides="Slide 23" codigo={`{itens.length === 0\n  ? <p>Seu carrinho está vazio.</p>\n  : <ItensCarrinho itens={itens} />}`}><ExemploCondicional /></Bloco>
        <Bloco numero="10" titulo="Formulário controlado" slides="Slide 24" codigo={`const [busca, setBusca] = useState('');\n\n<input\n  value={busca}\n  onChange={evento => setBusca(evento.target.value)}\n/>`}><ExemploFormulario /></Bloco>
        <Bloco numero="11" titulo="Estado elevado" slides="Slide 25" codigo={`function App() {\n  const [quantidade, setQuantidade] = useState(0);\n  return <>\n    <Botao onAdicionar={() => setQuantidade(q => q + 1)} />\n    <Resumo quantidade={quantidade} />\n  </>;\n}`}><ExemploEstadoElevado /></Bloco>
        <Bloco numero="12" titulo="useEffect e fetch" slides="Slide 26" codigo={`useEffect(() => {\n  fetch('./produtos.json')\n    .then(resposta => resposta.json())\n    .then(setProdutos);\n}, []);`}><ExemploEfeito /></Bloco>
        <Bloco numero="13" titulo="Bootstrap com className" slides="Slide 27" codigo={`<article className="card h-100">\n  <button className="btn btn-primary">\n    Adicionar\n  </button>\n</article>`}><div className="card shadow-sm"><div className="card-body"><h3 className="h5">Card Bootstrap</h3><button className="btn btn-primary">Adicionar</button></div></div></Bloco>
      </main>
    </>
  );
}

createRoot(document.getElementById('root')).render(<GaleriaExemplos />);
