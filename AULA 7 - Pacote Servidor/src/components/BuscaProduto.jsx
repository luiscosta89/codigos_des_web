export default function BuscaProduto({ busca, onMudar }) {
  return (
    <div className="mb-4">
      <label className="form-label fw-bold" htmlFor="busca-produto">Buscar produto</label>
      <input
        className="form-control form-control-lg"
        id="busca-produto"
        type="search"
        value={busca}
        onChange={(evento) => onMudar(evento.target.value)}
        placeholder="Ex.: teclado, mouse ou headset"
      />
      <div className="form-text">Campo controlado: o valor exibido vem do estado.</div>
    </div>
  );
}
