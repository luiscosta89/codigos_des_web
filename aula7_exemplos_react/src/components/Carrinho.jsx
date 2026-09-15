import { calcularTotal } from '../domain/carrinho.js';

function formatarPreco(valor) {
  return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

export default function Carrinho({ itens, onRemover }) {
  return (
    <section className="card shadow-sm border-0">
      <div className="card-body">
        <span className="eyebrow">ESTADO E RENDERIZAÇÃO CONDICIONAL</span>
        <h2 className="h4">Carrinho ({itens.length})</h2>
        {itens.length === 0 ? (
          <p className="text-secondary mb-0">Seu carrinho está vazio.</p>
        ) : (
          <>
            <ul className="list-group list-group-flush mb-3">
              {itens.map((item, indice) => (
                <li className="list-group-item px-0 d-flex justify-content-between align-items-center gap-2" key={`${item.id}-${indice}`}>
                  <span><strong>{item.nome}</strong><br /><small>{formatarPreco(item.preco)}</small></span>
                  <button className="btn btn-sm btn-outline-danger" type="button" onClick={() => onRemover(indice)} aria-label={`Remover ${item.nome}`}>Remover</button>
                </li>
              ))}
            </ul>
            <p className="d-flex justify-content-between fs-5 fw-bold mb-0" data-testid="cart-total">
              <span>Total</span><span>{formatarPreco(calcularTotal(itens))}</span>
            </p>
          </>
        )}
      </div>
    </section>
  );
}
