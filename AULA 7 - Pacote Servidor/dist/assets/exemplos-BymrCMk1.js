import{a as e,c as t,n,o as r,r as i,s as a}from"./produtos-e65E2kSL.js";var o=t(),s=a(),c=r(),l=[{id:1,nome:`Teclado Mecânico`,preco:199.9},{id:2,nome:`Mouse Sem Fio`,preco:89.9},{id:3,nome:`Headset Gamer`,preco:249.9}],u=e=>e.toLocaleString(`pt-BR`,{style:`currency`,currency:`BRL`});function d({numero:e,titulo:t,slides:n,codigo:r,children:i}){return(0,c.jsxs)(`article`,{className:`example-section`,children:[(0,c.jsxs)(`header`,{className:`example-heading`,children:[(0,c.jsx)(`span`,{className:`example-number`,children:e}),(0,c.jsxs)(`div`,{children:[(0,c.jsx)(`h2`,{children:t}),(0,c.jsx)(`small`,{children:n})]})]}),(0,c.jsxs)(`div`,{className:`example-columns`,children:[(0,c.jsx)(`pre`,{children:(0,c.jsx)(`code`,{children:r})}),(0,c.jsxs)(`div`,{className:`live-demo`,children:[(0,c.jsx)(`span`,{className:`live-label`,children:`RESULTADO EXECUTÁVEL`}),i]})]})]})}function f(){return(0,c.jsxs)(`article`,{className:`mini-product`,children:[(0,c.jsx)(`strong`,{children:`Teclado Mecânico`}),(0,c.jsx)(`span`,{children:`R$ 199,90`})]})}function p({nome:e,preco:t}){return(0,c.jsxs)(`article`,{className:`mini-product`,children:[(0,c.jsx)(`strong`,{children:e}),(0,c.jsx)(`span`,{children:u(t)})]})}function m(){let[e,t]=(0,o.useState)(l);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(`div`,{className:`mini-list`,children:e.map(e=>(0,c.jsx)(p,{...e},e.id))}),(0,c.jsx)(`button`,{className:`btn btn-sm btn-outline-primary mt-3`,onClick:()=>t(e=>n(e)),children:`Reordenar mantendo as keys`})]})}function h(){let[e,t]=(0,o.useState)(`Nenhum clique recebido.`);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(`button`,{className:`btn btn-primary`,onClick:()=>t(`Evento recebido pela função!`),children:`Disparar onClick`}),(0,c.jsx)(`p`,{className:`mt-3 mb-0`,role:`status`,children:e})]})}function g(){let[e,t]=(0,o.useState)(!1);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(`button`,{className:`btn btn-primary`,onClick:()=>t(!0),children:`Adicionar produto`}),e&&(0,c.jsx)(`div`,{className:`alert alert-success mt-3 mb-0`,role:`status`,children:`Produto adicionado com sucesso!`})]})}function _(){let[e,t]=(0,o.useState)(0);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsxs)(`p`,{className:`fs-5 fw-bold`,children:[`Contador: `,e]}),(0,c.jsx)(`button`,{className:`btn btn-primary`,onClick:()=>t(e=>e+1),children:`Incrementar`})]})}function v(){let[t,n]=(0,o.useState)([]),r=l[0];return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsxs)(`p`,{children:[`Itens: `,t.length]}),(0,c.jsxs)(`div`,{className:`d-flex gap-2`,children:[(0,c.jsx)(`button`,{className:`btn btn-sm btn-success`,onClick:()=>n(e=>i(e,r)),children:`Criar novo array + adicionar`}),(0,c.jsx)(`button`,{className:`btn btn-sm btn-outline-danger`,disabled:!t.length,onClick:()=>n(t=>e(t,0)),children:`Remover com filter`})]})]})}function y(){let[e,t]=(0,o.useState)([]);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(`p`,{className:`alert alert-light`,children:e.length===0?`Seu carrinho está vazio.`:`${e.length} produto no carrinho.`}),(0,c.jsx)(`button`,{className:`btn btn-sm btn-primary`,onClick:()=>t(e=>e.length?[]:[l[0]]),children:`Alternar estado`})]})}function b(){let[e,t]=(0,o.useState)(``),n=l.filter(({nome:t})=>t.toLowerCase().includes(e.toLowerCase()));return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(`label`,{htmlFor:`busca-demo`,className:`form-label`,children:`Buscar produto`}),(0,c.jsx)(`input`,{id:`busca-demo`,className:`form-control`,value:e,onChange:e=>t(e.target.value),placeholder:`Digite mouse`}),(0,c.jsxs)(`p`,{className:`mt-2 mb-0`,children:[`Resultado: `,n.map(({nome:e})=>e).join(`, `)||`nenhum`]})]})}function x({onAdicionar:e}){return(0,c.jsx)(`button`,{className:`btn btn-sm btn-primary`,onClick:e,children:`Filho emite ação`})}function S({quantidade:e}){return(0,c.jsxs)(`span`,{className:`badge text-bg-success`,children:[`Outro filho lê: `,e]})}function C(){let[e,t]=(0,o.useState)(0);return(0,c.jsxs)(`div`,{className:`d-flex flex-wrap align-items-center gap-3`,children:[(0,c.jsx)(x,{onAdicionar:()=>t(e=>e+1)}),(0,c.jsx)(S,{quantidade:e})]})}function w(){let[e,t]=(0,o.useState)(`Aguardando o efeito…`);return(0,o.useEffect)(()=>{let e=setTimeout(()=>t(`Dados sincronizados após a renderização.`),50);return()=>clearTimeout(e)},[]),(0,c.jsx)(`p`,{className:`alert alert-info mb-0`,children:e})}function T(){let e={nome:`Teclado Mecânico`,preco:199.9,estoque:4};return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(`header`,{className:`examples-hero`,children:(0,c.jsxs)(`div`,{className:`container py-5`,children:[(0,c.jsx)(`span`,{className:`eyebrow`,children:`AULA 7 · INTRODUÇÃO AO REACT`}),(0,c.jsx)(`h1`,{className:`display-5 fw-bold`,children:`Todos os exemplos React dos slides`}),(0,c.jsx)(`p`,{className:`lead`,children:`Código à esquerda e resultado executável à direita. Use os controles para observar a interface reagir ao estado.`}),(0,c.jsx)(`a`,{className:`btn btn-dark`,href:`./index.html`,children:`Voltar à TechStore completa`})]})}),(0,c.jsxs)(`main`,{className:`container py-4 examples-stack`,children:[(0,c.jsx)(d,{numero:`01`,titulo:`Componente funcional`,slides:`Slides 7, 10 e 13–14`,codigo:`function ProdutoCard() {
  return (
    <article>
      <h2>Teclado Mecânico</h2>
      <p>R$ 199,90</p>
    </article>
  );
}`,children:(0,c.jsx)(f,{})}),(0,c.jsxs)(d,{numero:`02`,titulo:`JSX e expressões`,slides:`Slides 11–12`,codigo:`const produto = { nome: 'Teclado', preco: 199.9 };

<h2>{produto.nome}</h2>
<p>{produto.preco.toFixed(2)}</p>`,children:[(0,c.jsx)(p,{nome:e.nome,preco:e.preco}),(0,c.jsxs)(`p`,{className:`mt-2 mb-0`,children:[`Estoque calculado no JSX: `,e.estoque>0?`${e.estoque} unidades`:`indisponível`]})]}),(0,c.jsx)(d,{numero:`03`,titulo:`Props`,slides:`Slide 15`,codigo:`function ProdutoCard({ nome, preco }) {
  return <p>{nome}: {preco}</p>;
}

<ProdutoCard nome="Mouse" preco={89.9} />`,children:(0,c.jsx)(p,{nome:`Mouse Sem Fio`,preco:89.9})}),(0,c.jsx)(d,{numero:`04`,titulo:`map e key`,slides:`Slides 16–17`,codigo:`{produtos.map(produto => (
  <ProdutoCard
    key={produto.id}
    produto={produto}
  />
))}`,children:(0,c.jsx)(m,{})}),(0,c.jsx)(d,{numero:`05`,titulo:`Eventos`,slides:`Slide 18`,codigo:`<button onClick={() => executarAcao()}>
  Disparar evento
</button>`,children:(0,c.jsx)(h,{})}),(0,c.jsx)(d,{numero:`06`,titulo:`Feedback visual e toast`,slides:`Slides 18, 23 e 27`,codigo:`const [visivel, setVisivel] = useState(false);

<button onClick={() => setVisivel(true)}>Adicionar</button>
{visivel && <div role="status">
  Produto adicionado com sucesso!
</div>`,children:(0,c.jsx)(g,{})}),(0,c.jsx)(d,{numero:`07`,titulo:`useState`,slides:`Slides 19–20`,codigo:`const [contador, setContador] = useState(0);

<button onClick={() => setContador(atual => atual + 1)}>
  Incrementar
</button>`,children:(0,c.jsx)(_,{})}),(0,c.jsx)(d,{numero:`08`,titulo:`Estado imutável`,slides:`Slides 21–22`,codigo:`setCarrinho(atual => [...atual, produto]);

setCarrinho(atual =>
  atual.filter((_, indice) => indice !== 0)
);`,children:(0,c.jsx)(v,{})}),(0,c.jsx)(d,{numero:`09`,titulo:`Renderização condicional`,slides:`Slide 23`,codigo:`{itens.length === 0
  ? <p>Seu carrinho está vazio.</p>
  : <ItensCarrinho itens={itens} />}`,children:(0,c.jsx)(y,{})}),(0,c.jsx)(d,{numero:`10`,titulo:`Formulário controlado`,slides:`Slide 24`,codigo:`const [busca, setBusca] = useState('');

<input
  value={busca}
  onChange={evento => setBusca(evento.target.value)}
/>`,children:(0,c.jsx)(b,{})}),(0,c.jsx)(d,{numero:`11`,titulo:`Estado elevado`,slides:`Slide 25`,codigo:`function App() {
  const [quantidade, setQuantidade] = useState(0);
  return <>
    <Botao onAdicionar={() => setQuantidade(q => q + 1)} />
    <Resumo quantidade={quantidade} />
  </>;
}`,children:(0,c.jsx)(C,{})}),(0,c.jsx)(d,{numero:`12`,titulo:`useEffect e fetch`,slides:`Slide 26`,codigo:`useEffect(() => {
  fetch('./produtos.json')
    .then(resposta => resposta.json())
    .then(setProdutos);
}, []);`,children:(0,c.jsx)(w,{})}),(0,c.jsx)(d,{numero:`13`,titulo:`Bootstrap com className`,slides:`Slide 27`,codigo:`<article className="card h-100">
  <button className="btn btn-primary">
    Adicionar
  </button>
</article>`,children:(0,c.jsx)(`div`,{className:`card shadow-sm`,children:(0,c.jsxs)(`div`,{className:`card-body`,children:[(0,c.jsx)(`h3`,{className:`h5`,children:`Card Bootstrap`}),(0,c.jsx)(`button`,{className:`btn btn-primary`,children:`Adicionar`})]})})})]})]})}(0,s.createRoot)(document.getElementById(`root`)).render((0,c.jsx)(T,{}));