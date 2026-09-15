import { useEffect, useMemo, useState } from 'react';
import Navbar from './components/Navbar.jsx';
import BuscaProduto from './components/BuscaProduto.jsx';
import ListaProdutos from './components/ListaProdutos.jsx';
import Carrinho from './components/Carrinho.jsx';
import Feedback from './components/Feedback.jsx';
import GuiaExemplos from './components/GuiaExemplos.jsx';
import { adicionarAoCarrinho, removerDoCarrinho } from './domain/carrinho.js';
import { filtrarProdutos, reordenarProdutos } from './domain/produtos.js';
import { resolverAsset } from './domain/caminhos.js';
import { PRODUTOS_PADRAO } from './data/produtos.js';

export default function App() {
  const abertoComoArquivo = window.location.protocol === 'file:';
  const [produtos, setProdutos] = useState(abertoComoArquivo ? PRODUTOS_PADRAO : []);
  const [carrinho, setCarrinho] = useState([]);
  const [busca, setBusca] = useState('');
  const [mensagem, setMensagem] = useState('');
  const [carregando, setCarregando] = useState(!abertoComoArquivo);
  const [erro, setErro] = useState('');

  useEffect(() => {
    if (abertoComoArquivo) return undefined;
    let ativo = true;
    fetch(resolverAsset('produtos.json'))
      .then((resposta) => {
        if (!resposta.ok) throw new Error('Não foi possível carregar os produtos.');
        return resposta.json();
      })
      .then((dados) => ativo && setProdutos(dados))
      .catch((falha) => ativo && setErro(falha.message))
      .finally(() => ativo && setCarregando(false));
    return () => { ativo = false; };
  }, [abertoComoArquivo]);

  const produtosVisiveis = useMemo(
    () => filtrarProdutos(produtos, busca),
    [produtos, busca],
  );

  function adicionar(produto) {
    setCarrinho((itensAtuais) => adicionarAoCarrinho(itensAtuais, produto));
    setMensagem(`Produto adicionado: ${produto.nome}.`);
  }

  function remover(indice) {
    setCarrinho((itensAtuais) => removerDoCarrinho(itensAtuais, indice));
  }

  return (
    <>
      <Navbar quantidade={carrinho.length} />
      <main className="container py-4">
        <section className="hero rounded-4 p-4 p-lg-5 mb-4" aria-labelledby="titulo-aula">
          <span className="eyebrow">AULA 7 · INTRODUÇÃO AO REACT</span>
          <h1 id="titulo-aula" className="display-6 fw-bold mt-2">TechStore: estado vira interface</h1>
          <p className="lead mb-0">Um projeto executável que reúne os exemplos de JSX, componentes, props, listas, eventos, estado, efeitos e Bootstrap apresentados nos slides.</p>
        </section>

        <div className="row g-4 align-items-start">
          <section className="col-lg-8" aria-labelledby="titulo-vitrine">
            <div className="d-flex flex-wrap gap-3 justify-content-between align-items-end mb-3">
              <div>
                <span className="eyebrow">COMPONENTES + PROPS + MAP + KEY</span>
                <h2 id="titulo-vitrine" className="h3 mb-0">Vitrine de produtos</h2>
              </div>
              <button className="btn btn-outline-secondary" type="button" onClick={() => setProdutos((atuais) => reordenarProdutos(atuais))}>
                Reordenar e testar keys
              </button>
            </div>
            <BuscaProduto busca={busca} onMudar={setBusca} />
            {carregando && <p className="alert alert-info">Carregando produtos…</p>}
            {erro && <p className="alert alert-danger" role="alert">{erro}</p>}
            {!carregando && !erro && (
              <ListaProdutos produtos={produtosVisiveis} onAdicionar={adicionar} />
            )}
          </section>

          <aside className="col-lg-4 sticky-lg-top cart-column" aria-label="Resumo do carrinho">
            <Carrinho itens={carrinho} onRemover={remover} />
          </aside>
        </div>

        <GuiaExemplos />
      </main>
      <Feedback mensagem={mensagem} onFechar={() => setMensagem('')} />
    </>
  );
}
