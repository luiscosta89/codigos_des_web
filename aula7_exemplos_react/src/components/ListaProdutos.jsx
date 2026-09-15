import ProdutoCard from './ProdutoCard.jsx';

export default function ListaProdutos({ produtos, onAdicionar }) {
  if (produtos.length === 0) {
    return <p className="alert alert-warning">Nenhum produto corresponde à busca.</p>;
  }

  return (
    <div className="row g-3">
      {produtos.map((produto) => (
        <ProdutoCard key={produto.id} produto={produto} onAdicionar={onAdicionar} />
      ))}
    </div>
  );
}
