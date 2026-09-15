export default function Navbar({ quantidade }) {
  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Navegação principal">
      <div className="container">
        <a className="navbar-brand fw-bold" href="#topo">TechStore React</a>
        <div className="d-flex align-items-center gap-2 text-white">
          <span>Carrinho</span>
          <span className="badge rounded-pill text-bg-primary" aria-label="Itens no carrinho">{quantidade}</span>
        </div>
      </div>
    </nav>
  );
}
